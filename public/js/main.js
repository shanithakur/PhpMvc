var URLROOT = $('#url_root').val();

/**
 * Get menu items from data base
 * @loads append to navbar
 */
$(document).ready(function(){
   $.ajax({
       url: URLROOT+'/Admins/getMenus',
       method: 'POST',
       success: function (result) {
         var data = JSON.parse(result);
         $.each(data, function(key, value) {
            $('.fill-menu-items').append('<li class="nav-item"><a class="nav-link" href="'+URLROOT+'/pages/'+value.link+'" target="'+value.target+'">'+value.label+'</a></li>')
         });
        }
    })
});


/**
 * AJAX call to likeComment method
 * @param comment_id
 * @param post_id
 * @return json
 * total no of likes dislikes
 */
function likeComment(comment_id, post_id){
   $.ajax({
       url: URLROOT+'/comments/likeComment',
       method: 'POST',
       data : {comment_id: comment_id, post_id:post_id},
       success: function (result) {
       console.log(result);
         var data = JSON.parse(result);

         if(result){
            $("#likes"+comment_id).text(data.likes);
            $("#dislikes"+comment_id).text(data.dislikes);
            $("disable-like-btn"+comment_id).attr('disabled','true');
         }
        }
    });
}

/**
 * AJAX call to dislikeComment
 * @param comment_id
 * @param post_id
 * @return json
 * total no of likes dislikes
 */
function dislikeComment(comment_id, post_id){
   $.ajax({
       url: URLROOT+'/comments/dislikeComment',
       method: 'POST',
       data : {comment_id: comment_id, post_id:post_id},
       success: function (result) {
        //console.log(result);
         var data = JSON.parse(result);
         if(result){
            $("#likes"+comment_id).text(data.likes);
            $("#dislikes"+comment_id).text(data.dislikes);
            $("disable-like-btn"+comment_id).attr('disabled','true');
         }
        }
    });
}

