<?php 

   $thecurpage = $_GET['page'];

   function get_existing_postid($videoID){

      $q_arg = array(
            'meta_query' => array(
                array(
                    'key' => 'videoid',
                    'value' => $videoID
                )
            ),
            'post_type' => 'youtube_videos',
            'posts_per_page' => 1
        ); 
      $myquery = new WP_Query($q_arg);

      if($myquery->have_posts()){

        while($myquery->have_posts()){

            $myquery->the_post();

            $postID =  get_the_ID();


        }

      }

      return $postID;

   }
?>

<div class="container" style="max-width:100%;">

  <div class="row text-center">
      <div class="col-sm col-md-6 p-3">
        <h4>Click here to import all youtube videos from channel</h4>
        <form method="get" action="">
            <input type="hidden" value="<?php echo($thecurpage); ?>" name="page" />
            <input type="hidden" value="import" name="action" />
            <button type="submit" class="btn btn-success btn-lg">Import</button>
        </form>
      </div>

       <div class="col-sm col-md-6 p-3">
          <h4>Delete All youtube Videos</h4>
          <form method="get" action="">
            <input type="hidden" value="<?php echo($thecurpage); ?>" name="page" />
            <input type="hidden" value="delete" name="action" />
            <button type="submit" class="btn btn-danger btn-lg">Delete All</button>
          </form>
      </div>

  </div>  

  
</div>

<?php

$theaction = '';
$blnImport = false; 
$blnDelete = false;
if (isset($_GET['action'])){
  //set the action
  $theaction = $_GET['action'];
}


if ($theaction == 'import'){

    //first get all the posts
    $allWPVidPosts = get_posts( array('post_type' => 'youtube_videos', 'numberposts' => 2500, 'order' => 'ASC') );
    $compvids = '';

     if (count($allWPVidPosts) != 0){
     
        //we know we have videos so CYCLE THEM
        foreach ($allWPVidPosts as $eachpost){
          
          $postID = $eachpost->ID;
          
          $videIDField = get_field('videoid', $postID);

          // echo '<pre>';
          // print_r($eachpost->ID);
          // echo '</pre>';

          if($videIDField  == ''){
            //do nothing
          } else {
            //this is a video
            $compvids = ',' . $compvids . $videIDField  . ',';
          }
        

        }

     } 

    
     

	//echo '<h1>This is import Action</h1>';
    $theyoutubekey = get_option( 'youtubeAPIKey' );
    $thechannelid = get_option( 'youtubeChannelID' );

    $videoList = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$thechannelid.'&maxResults='.'4'.'&key='.$theyoutubekey.''));
    // echo '<pre>';
    // print_r($videoList);
    // echo '</pre>';
    //sort through the items and output
    foreach($videoList->items as $item){
      //loop through the videos and add them as custom post types

        $videxists = strpos($compvids , $item->id->videoId);


        $ytVideoID =  $item->id->videoId;
        $ytChannelId =  $item->snippet->channelId;
        $imageresmed     = $item->snippet->thumbnails->medium->url;
        $highImage = $item->snippet->thumbnails->high->url;



        if ($videxists > 0) {

          $existPostId = get_existing_postid($ytVideoID);

            //INSERT A NEW POST VIDEO
            $data = array(
              'ID'   => $existPostId,
              'post_title' => $item->snippet->title,
              'post_content' => $item->snippet->description,
              'post_status' => 'publish',
              'post_type' => 'youtube_videos'
            );

            $result = wp_update_post( $data );

           if ( $result && ! is_wp_error( $result ) ) {
            
                $thenewpostID = $result;


                $timeformat =  strtotime($item->snippet->publishedAt);
                

                update_field( 'videoid', $item->id->videoId, $thenewpostID);
                update_field( 'channelId', $item->snippet->channelId,  $thenewpostID);
                update_field( 'publishedAt', $item->snippet->publishedAt,  $thenewpostID);
                update_field( 'ydescription', $item->snippet->description,  $thenewpostID);

                //$uploadImage = media_sideload_image( $imageresmed, $thenewpostID, '', 'id');
                //$uploadImageHigh = media_sideload_image( $highImage, $thenewpostID, '', 'id');

                //update_field('imageresmed', $uploadImage, $thenewpostID);
                //set_post_thumbnail( $thenewpostID, $uploadImageHigh );

            }
        }else{

            //INSERT A NEW POST VIDEO
            $data = array(
              'post_title' => $item->snippet->title,
              'post_content' => $item->snippet->description,
              'post_status' => 'publish',
              'post_type' => 'youtube_videos'
            );

          //insert this post into the DB and RETRIEVE the ID
          $result = wp_insert_post( $data );
          if ( $result && ! is_wp_error( $result ) ) {
            
                $thenewpostID = $result;

                update_field( 'videoid', $item->id->videoId, $thenewpostID);
                update_field( 'channelId', $item->snippet->channelId,  $thenewpostID);
                update_field( 'publishedAt', $item->snippet->publishedAt,  $thenewpostID);
                update_field( 'ydescription', $item->snippet->description,  $thenewpostID);
               
                $uploadImage = media_sideload_image( $imageresmed, $thenewpostID, '', 'id');
                $uploadImageHigh = media_sideload_image( $highImage, $thenewpostID, '', 'id');

                update_field('imageresmed', $uploadImage, $thenewpostID);
                set_post_thumbnail( $thenewpostID, $uploadImageHigh );

            }
        }
        
        $blnImport = true;
    
      
      
  
        //add the youtube meta data
        // add_post_meta( $thenewpostID, 'videoid', $item->id->videoId);
        // add_post_meta( $thenewpostID, 'publishedAt', $item->snippet->publishedAt);
        // add_post_meta( $thenewpostID, 'channelId', $item->snippet->channelId);
        // add_post_meta( $thenewpostID, 'ytitle', $item->snippet->title);
        // add_post_meta( $thenewpostID, 'ydescription', $item->snippet->description);
        // add_post_meta( $thenewpostID, 'imageresmed', $item->snippet->thumbnails->medium->url);
        // add_post_meta( $thenewpostID, 'imagereshigh', $item->snippet->thumbnails->high->url);

        //set the import to true
    



    }
}



if ($theaction == 'delete'){
  //delete all videos with our custom post type
  $allWPVidPosts = get_posts( array('post_type' => 'youtube_videos', 'numberposts' => 100) );
  //loop through and delete all the posts
  foreach ($allWPVidPosts as $eachpost){
    wp_delete_post($eachpost->ID, true);
    $blnDelete = true;
  }
}

//output user action complete
if ($blnImport == true){
  ?>
    <br><br>
    <div class="alert alert-success">
      <h2>You have successfully imported videos from YouTube!</h2>
    </div>
  <?php
} elseif ($blnDelete == true){
  ?>
    <br><br>
    <div class="alert alert-danger">
      <h2>You have successfully deleted videos from the database!</h2>
    </div>
  <?php
}