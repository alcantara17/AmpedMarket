        <div class="span9 mainContent" id="your-account">
          <h2>User: <? echo $user['userName'] ?></h2>

          <div class="container-fluid">
            <div class="row-fluid">
              <div class="span9 btn-group">
                <? echo anchor('messages/send/'.$user['userHash'],'Message this user', 'class="btn"');?>
              </div>
            </div>

            <div class="row-fluid">
              <div class="span2"><strong>Date Registered</strong></div>
              <div class="span7"><? echo $user['dispTime'];?></div>
            </div>

	          <?php if($profileMessage !== NULL){ ?>
            <div class="row-fluid">
              <div class="span2"><strong>Profile Message</strong></div>
              <div class="span8 well"><? echo $profileMessage;?></div>
            </div>
            <?php } ?>

	          <?php if($user['userRole'] == 'vendor'){?>
		          <div class='reviews'>
			          Rating: <? echo $reviews['AvgRating'];?><br />
			          <?php 
			          if(count($reviews['reviews']) > 0){?>
			          <table class="orderlist">
			          <tr class="orderHeader">
				          <td>Rating</td>
				          <td>Comments</td>
				          <td>Time</td>
			          </tr>
			
			          <?php foreach($reviews['reviews'] as $review):?>
			          <tr>
				          <td><? echo $review['rating'];?>/5</td>
				          <td><? echo $review['reviewText'];?></td>
				          <td><? echo $review['time'];?></td>
			          </tr>
			          <?php endforeach; ?>
			          </table/>
			          <?php } else { ?>
			          This vendor has no reviews at present.
			          <?php } ?>		
                                  <div class="clear"></div>
		          </div>
	          <?php } ?>

			      <?php if(isset($user['publicKey'])) { ?>
              <div class="row-fluid">
                <div class="span2"><strong>PGP Public Key</strong></div>
                <pre id="publicKeyBox" class="span8 well"><? echo $user['publicKey']?></pre>
              </div>
			      <?php } ?>

            </div>
          </div>



