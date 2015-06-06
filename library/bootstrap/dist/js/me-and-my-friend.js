$(function() {

    //destination auto suggest ajax call
    $("#tagFriends").select2({
        placeholder: "type your friend first name",
        minimumInputLength: 2,
        ajax: {
            url: "?rt=createPost/getAutoSuggestion",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term // search term
                };
            },
            results: function (data, page) { // parse the results into the format expected by Select2.
                console.log(data);
                return {
                    results: data.friends
                };
            }
        },
        formatResult: function(data) {
            return data.name;
        },
        formatSelection: function(data) {
            return data.name;
        },
        multiple: true,
        initSelection : function (element) {
            var data = jQuery.parseJSON(element.val());
            element.val('');
            return data;
        }
    });

    $("#showMore").click(function(){

        //do ajax call get the new posts and append it to newsfeed
        $.ajax({
            url: "index/getUserPosts",
            cache: false,
            method: "POST",
            data: { page_number : $("#showMore").data("page-number") },
            dataType:'json'
        })
            .done(function( data ) {
                var st = '';
                var i =0;
                var alignst='';

                $.each(data.posts,function(index,value){

                    if(i%2!=0)
                        alignst=' class="timeline-inverted" ';
                    else
                        alignst='';

                    var profileUrl= "http://graph.facebook.com/"+value.facebook_id+"/picture";
                    st= st
                        +'<li'+ alignst+'>'
                        +'<div class="timeline-badge"><img class="img-circle" src='+profileUrl+'></div><div class="timeline-panel"><div class="timeline-heading"><p><small class="text-muted"><i class="fa fa-clock-o"></i>'
                        + value.created_date+'</small></p></div><div class="timeline-body"><p>'
                        +value.content+'</p><p style="text-align: right">Posted By:'+value.post_by+'</p></div></div></li>'

                    i++;
                });
                //change page number for second page hide if there is no next page

                var nextPage= $("#showMore").data("page-number")+1;
                $("#showMore").data('page-number',nextPage);

                if(data.loadNextPage==0){
                    $("#showMore").hide();
                }
                $("#newsFeeds").append(st);
            });
    })


});
