<?php
/* 
*	ajax add apply job
*/
add_action( 'wp_ajax_nopriv_et_upload_files', 'et_ajax_upload_files');
add_action( 'wp_ajax_et_upload_files', 'et_ajax_upload_files');
function et_ajax_upload_files(){
	header( 'HTTP/1.0 200 OK' );
	header( 'Content-type: application/json' );

	try{
		if ( !check_ajax_referer( 'apply_docs_et_uploader', '_ajax_nonce', false ) ){
			throw new Exception( __('Security error!', ET_DOMAIN ) );
		}

		// check fileID
		if(!isset($_POST['fileID']) || empty($_POST['fileID']) ){
			throw new Exception( __('Missing image ID', ET_DOMAIN ) );
		}
		else {
			$fileID	= $_POST["fileID"];
		}

		if(!isset($_FILES[$fileID])){
			throw new Exception( __('Uploaded file not found',ET_DOMAIN) );
		}

		// handle file upload				
		$attach_id	=	et_process_file_upload( $_FILES[$fileID], 0 , 0, array(
							'pdf'		=> 'application/pdf',
							'doc|docx'	=> 'application/msword',
							'odt'		=> 'application/vnd.oasis.opendocument.text',
							'zip'		=> 'application/zip',
							'rar'		=> 'application/rar',
							)
						);

		if ( is_wp_error($attach_id) ){
			throw new Exception( $attach_id->get_error_message() );
		}

		// no errors happened, return success response
		$res	= array(
			'success'	=> true,
			'msg'		=> __('The file was uploaded successfully', ET_DOMAIN),
			'data'		=> $attach_id
		);
	}
	catch(Exception $e){
		$res	= array(
			'success'	=> false,
			'msg'		=> $e->getMessage()
		);
	}

	echo json_encode($res);
	exit;
}

add_action( 'wp_ajax_nopriv_et_apply_job', 'et_ajax_insert_apply');
add_action( 'wp_ajax_et_apply_job', 'et_ajax_insert_apply');
function et_ajax_insert_apply () {

	$job_id 	= 	isset( $_POST['job_id']) 		? $_POST['job_id'] 	 : '';
	$email  	= 	isset( $_POST['apply_email']) 	? trim( $_POST['apply_email'] )	: '';
	$emp_name	=	isset( $_POST['apply_name']) 	? trim( $_POST['apply_name'] )	: '' ;
	$apply_note =   isset( $_POST['apply_note']) 	? trim( $_POST['apply_note'] )	: '' ;

	setcookie('seeker_name', $emp_name , time() + 3600*24*7, "/");
	setcookie('seeker_email', $email , time() + 3600*24*7, "/");

	$attachs	=	( isset( $_POST['attachments']) && is_array($_POST['attachments']) && !empty($_POST['attachments']) )	? $_POST['attachments'] : array();

	header( 'HTTP/1.0 200 OK' );
	header( 'Content-type: application/json' );
	
	try{

		if ( !check_ajax_referer( 'apply_docs_et_uploader', '_ajax_nonce', false ) ){
			throw new Exception( __('Security error!', ET_DOMAIN ) );
		}
		
		$valid	=	apply_filters( 'je_validate_application_form', array('valid' => true), $_POST );
		
		if(!$valid['valid']) {
			if(isset($valid['message']))
				throw new Exception($valid['message'] );
			else
				throw new Exception("Data input invalid");
		}

		if( !et_validate('email', $email) || $emp_name == '' ) {
			if( $emp_name == '' ) { 
				// employee name invlid			
				throw new Exception( __( "Please enter your name", ET_DOMAIN ) );
			} else {
				// email invalid
				throw new Exception( __( "Please enter your valid email address", ET_DOMAIN ) );
			}
		}

		$job = get_post( $job_id );
		if ($job == null || $job->post_status != 'publish') {
			// job request invalid or expired, pending, draft
			throw new Exception( __("This job is not available for application yet", ET_DOMAIN ) );
		}

		// check email available to apply job
		$email_valid	=	et_job_apply_validate($email, $job_id);
		if( !$email_valid['success'] ) {
			throw new Exception( $email_valid['msg'] );
		}

		$application = et_insert_application (
			array(
				'emp_email'		=> $email,
				'emp_name'		=> $emp_name,
				'apply_note'	=> $apply_note,
				'job_id'		=> $job_id,
				'company_id'	=> $job->post_author
			)
		);

		if( $application instanceof WP_Error ){
			throw new Exception( $application->get_error_message() );
		}

		

		$res	=	je_application_mail ($job, $application, $attachs , array('email' => $email, 'emp_name' => $emp_name, 'apply_note' => $apply_note) );		
		if( !$res)
			throw new Exception( __('An unknown error occurred while sending email.', ET_DOMAIN ) );
	}
	catch(Exception $e){
		$res = array(
			'success'	=> false,
			'msg'		=> $e->getMessage()
		);
	}

	// send response to user browser
	echo json_encode($res);
	exit;
}

/**
 * share job through mail
 */
add_action('wp_ajax_et_remind_job', 'et_remind_job');
add_action('wp_ajax_nopriv_et_remind_job', 'et_remind_job');

function et_remind_job () {
	
	$job_id 	= 	isset( $_POST['job_id']) 		? $_POST['job_id'] 	 		: '';
	$email  	= 	isset( $_POST['share_email']) 	? $_POST['share_email'] 	: '';
	$apply_note =   isset( $_POST['share_note']) 	? $_POST['share_note'] 		: '';
	
	$response	=	array ();
	if( !et_validate('email', $email)) {
		$response['success']	=	false;
		$response['msg']		=	__("Email invalid", ET_DOMAIN);	
	}
	
	$job 		=	get_post ( $job_id );
	
	$message	=	$apply_note;
	$subject	=	sprintf(__("You have saved this job for later review: %s", ET_DOMAIN),$job->post_title);

	$message	=	apply_filters('et_share_job_message', $message, $job_id ,$email);
	$subject	=	apply_filters('et_share_job_title', $subject, $job_id ,$email);
	
	if ( $job == null || $job->post_status != 'publish') {
		// job request invalid or expired, pending, draft
		$response['success']	=  false;
		$response['msg']		=	__("Sorry! The job you requested is not available now!",ET_DOMAIN);
		
	} else { // job valid
		//$message	.=	'<br/>'.__("You can view the job",ET_DOMAIN).' <a href="'.get_permalink($job_id).'">'.__("here",ET_DOMAIN).'</a>';
		$mail_ok	=	je_remind_job_mail ( $email, $subject, et_get_mail_header(). $message .et_get_mail_footer() );
		
		if( $mail_ok ) {
			$response['success']	=	true;
			$response['msg']		=	__('<span class="msg">A reminder about this job has been sent to your email. Good luck!</span>',ET_DOMAIN);
		}else {
			$response['success']	=	false;
			$response['msg']		=	__("There is something wrong in the mailing process. Please contact the administrators for more information!",ET_DOMAIN);
		}
	}
	// give respone to user browser
	header( 'HTTP/1.0 200 OK' );
	header( 'Content-type: application/json' );
	echo json_encode($response);
	exit;
}

/**
 * insert application when user apply a job
 * @param unknown_type $args
 */
function et_insert_application( $args = array () ) {


	$args = wp_parse_args( $args, array(
		'emp_email' 		=> '',
		'emp_name'			=> 'enginetheme',
		'apply_note'		=> '',
		'job_id'			=> '',
		'post_author'		=> 1 ,
		'company_id'		=> ''
	));
	

	$args['post_status']	=	'publish' ;
	$args['post_type']		=	'application';
	
	$args	=	apply_filters('et_apply_job', $args );
	$check_valid	=	et_job_apply_validate($args['emp_email'], $args['job_id']);
	if ( !$check_valid['success'] ) {
		return new WP_Error(406, $check_valid['msg']);
	}
	try {
		$post_id = et_insert_post(array(
			'post_title' 		=> 	get_the_title($args['job_id']),
			'post_content' 		=> 	$args['apply_note'],
			'post_status' 		=> 	$args['post_status'],
			'post_type' 		=> 	$args['post_type'],
			'post_parent'		=>	$args['job_id'],
			'emp_email'			=>	$args['emp_email'],
			'emp_name'			=>	$args['emp_name'],
			'post_author'		=>	$args['post_author'],
			'company_id'		=>	$args['company_id']
		));
		
	} catch (Exception $e) {
		return new WP_Error($e->getCode(), $e->getMessage());
	}
	
	do_action ('et_insert_application', $post_id, $args );
	
	return $post_id;
}
/**
 * check an email is valid to apply a job
 * 	- email valid 
 *  - job valid
 *  - have email applied to job already?
 * @param string $email
 * @param int $job : job id
 */
function et_job_apply_validate ( $email, $job ) {
	// valid email
	if( !et_validate('email', $email)) 
		return array ('success' => false, 'msg' => __('Your email address is invalid!', ET_DOMAIN));
	// valid job
	if(get_post_status($job) != 'publish') 
		return array ('success' => false, 'msg' => __('This job is not available for application yet!', ET_DOMAIN));
	// validate job and email
	$wp_query	=	new WP_Query( array ( 
									'post_parent' => $job, 
									'post_type' => 'application',
									'meta_key'	=>	'et_emp_email',
									'meta_value'=> $email
								)
					);
	if( $wp_query->have_posts() ) 
		return array ('success' => false, 'msg' => __('This email address has already been used to apply for this job.', ET_DOMAIN));
		
	return array ('success' => true, 'msg' => __('Valid!', ET_DOMAIN));;
}



/**
 * add job column in application list
*/
add_action( 'manage_application_posts_custom_column' , 'et_application_custom_columns', 10, 2 );

function et_application_custom_columns( $column, $post_id ) {
    if($column == 'job_id') {
    	global $post;
    	$job	=	get_post($post->post_parent);
    ?>
    	<a href="<?php echo admin_url( 'edit.php?post_type=application&post_parent='.$job->ID ); ?>" > <?php echo $job->post_title ?> </a>
    <?php 
    }
}

/* 
 * Add custom column to application list 
*/
function et_add_application_column( $columns ) {
	$first				= array_slice($columns, 0, 2);	
	$temp 				= array_slice($columns, 2);
	$first['job_id']	= __( 'Job', ET_DOMAIN );

    $columns	=  array_merge( $first , $temp  );
    return $columns;
}
add_filter( 'manage_application_posts_columns' , 'et_add_application_column' );


/**
 * filter application list in admin by job id
*/
add_action('pre_get_posts', 'et_filter_application_list');
function et_filter_application_list ($query) {
	if(isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'application') {
		if(isset($_REQUEST['post_parent'])) {
			$query->set('post_parent',$_REQUEST['post_parent'] );
		}
	}
	return $query;
}