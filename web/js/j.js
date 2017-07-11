function editPreviewText() {
    $('p').each(function () {
        var previewTextLimit = 100;
        var blogText = $(this).html();
        if (blogText.length > previewTextLimit) {
            var truncatedText = blogText.substring( 0, previewTextLimit) + '....';
            $(this).text(truncatedText);
        }
    });
}

function addLike(postId) {
    $.ajax({
        type: "POST",
        url: 'like?id=' + postId,
        data: {
            post_id: postId
        },
        success: function (response) {
            if (response === false) {
                alert('Unable to add a "like" !');
            } else if (response === 'liked') {
                alert("You already liked this post !!!!");
            } else if (response == 'guest') {
                alert('You need to be logged in to like');
            } else {
                $('#like-' + postId).html(response);
            }
        },
        error: function (response) {
            
            alert('Failed to add like. Try to relogin');
        }
    });
}

function openCommentForm(postId){
   $('#modal-'+ postId).modal('show');
 $('#modalContent-' + postId).load('/post/comment?id='+ postId);
 
}

function addMember(userId, communityId) {
    $.ajax({
        type: "POST",
        url: 'add-members',
        data: {
            Members: {
                user_id: userId,
                community_id: communityId
            }
        }
    }).done(function (response) {
        alert(response);
    });
}

function checkMemberStatus(loginUser, testUser, event) {
    if (loginUser !== testUser) {
        alert('Only creator can manipulate community');
        event.preventDefault();
    }   
}

var listCommunityId = ''
  
function responseForList(data) {
    if (data === 'true') {       
        $(document).ready(function ()
        {
            var href = $('#'+ listCommunityId ).attr('href');
            window.location.href = href;
        });
    }else{
       alert("Only members can add and view posts !!! "); 
    }
}

function isMember(testUser, communityId, event, linkId) {   
    listCommunityId = linkId;
    $.ajax({
        type: "POST",
        url: 'is-member?id=' + communityId,

        data: {
            userId: testUser,
        },
        success: function (data) {
            responseForList(data);
        },
        error: function () {
            alert('failed to access Create Post');
        }
    });
    event.preventDefault();
}

