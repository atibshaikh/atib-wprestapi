<div class="container">
  <div class="row p-3">
    <h1>General API For Youtube API Importer</h1>
    <hr>
  </div>
  <div class="row">
    <div class="col">
      <div class="bg-api">
          <div class="bg-api-wrap">
              <div class="top-form-field">
                  <h2 class="display-6">YouTube API Importer</h2>
                    <p class="lead">Use this section to save your API key and channel ID for video imports.</p>
                    <hr class="my-4">
                     <p>Need a YouTube Key generated? They're free! Get one here.</p>
                      <form method="post" action="options.php">
                        <?php
                        settings_fields( 'youtubeapicustomsettings' );
                        do_settings_sections( 'youtubeapicustomsettings' )
                        ?>
                        <div class="form-group">
                          <label for="youtubeAPIKey">YouTube API Key</label>
                          <input name="youtubeAPIKey" value="<?php echo get_option( 'youtubeAPIKey' ); ?>" type="text" class="form-control" id="youtubeapikey" placeholder="Your YouTube API Key">
                        </div>
                        <div class="form-group">
                          <label for="youtubeChannelID">Your YouTube Channel ID:</label>
                          <input type="text" name="youtubeChannelID" value="<?php echo get_option( 'youtubeChannelID' ); ?>" class="form-control" id="youtubeChannelID" placeholder="YouTube Channel ID">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
              </div>
          </div>
      </div>
    </div>

  </div>
</div>