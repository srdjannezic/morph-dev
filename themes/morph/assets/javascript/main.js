//-- Hover Logo animatioin 

var can_play = true;
if($(window).width() > 1024) {
    $('body').on('mouseenter','.logo',function(){
        //var loaded = bodymovin.loadAnimation(animationLogo);
        $(this).addClass('ease');
        if(can_play){
            loaded.destroy();
            loaded = bodymovin.loadAnimation(animationLogo);
            can_play = false;
        }
    });
}

$('body').on('mouseleave','.logo',function(){
    setTimeout(function(){
         $(this).removeClass('ease');
    }, 1000)
   
    can_play = false; //reset
    setTimeout(function(){
        can_play = true;
    },1000);
})

//-- Menu Btn

$('.menu-btn').click(function(){
	$(this).toggleClass('cross');
	$('.nav-menu').toggleClass('open');
    setTimeout(function(){
        $('.header').toggleClass('in-menu');
    },300);
	
});

$(window).bind('mousewheel', function(e){
	var video = $('.autoplay');
	if(video){
        video.each(function(index,item){
		  item.play();
          //console.log('test');
        });
    }
    if($(window).width() > 990) {
        $('.page-home .morph-move, #fp-nav.fp-right, #bd-morph').addClass('start');    
    }

	
	$('#bd-morph').hide();
}); 

//-- About page on scroll change color of contact

 if($('.page-about').length > 0) {
     $(window).scroll(function(){
        const aboutFirstWhite = $('.section-0').offset().top;
        const heroAbout = $('.page-about .hero-slider').offset().top;
        let windowScrollTop = $(window).scrollTop();
        const logoAbout = $('.logo');
        const menuBtnAbout = $('.menu-btn');
        const contactBtn = $('.btn-contact');
        const fade = $('.bottom-fade');
        if(windowScrollTop > aboutFirstWhite - 80) {
           logoAbout.addClass('changed');
           menuBtnAbout.addClass('changed');
           
        }
        else {
            logoAbout.removeClass('changed');
            menuBtnAbout.removeClass('changed');
            
        }
        if(windowScrollTop > 100) {
            contactBtn.addClass('changed');
            //fade.addClass('changed');
        }
        else {
            contactBtn.removeClass('changed');
            //fade.removeClass('changed');
        }
    });
} 

$('#about-pg,#single-pg').on('click', '.pagination > li > a', function (event) {
   // reference the href attribute of the list item anchor tag
    var page = $(this).attr('href');

    event.preventDefault(); 
    if ($(this).attr('href') != '#') {
        //$("html, body").animate({scrollTop: 0}, "slow");
        $('body').find('form.filterPosts').request('onFilterPosts', {  
            data: {page: page},
            update:{
                'posts':'.post-list'
            },
            success: function(data){
                $('.filterPosts').html(data.posts);
               // getVideosThumbs(); 
            },
        });
    }
    
});


//load more
$('#blog-pg').on('click', '.pagination > li > a', function (event) {
   // reference the href attribute of the list item anchor tag
    var page = $(this).attr('href');

    event.preventDefault(); 
    $('.load-more').hide();
    $('.blog-loader').show();
    if ($(this).attr('href') != '#') {
        $('body').find('form.filterPosts').request('onFilterPosts', { 
            data: {page: page,
                type: 'loadmore'},
            update:{
                'posts':'.post-list'
            },
            success: function(data){
                $('.filterPosts').html(data.posts);
                //getVideosThumbs(); 
                $('.load-more').show();
                $('.blog-loader').hide();
            },
        });
    }
});

var video = document.getElementById("morph-video");

//getVideosThumbs();

var video_clicked = 0;
var currentVideo;    
var is_played = false;

$('.panel-title > a').click(function(){
    var panelID = $(this).attr('href');
    $(".panel-collapse").addClass('out');
    $(panelID).removeClass('out');
    $('.panel-heading').removeClass('expand');
    $(panelID).addClass('in');
    $(this).closest('.panel-heading').addClass('expand');
});

$('.get-directions').click(function(){

    $('#directionsModal').modal('show');
});

//trigger first question modal
$('.do-test').click(function(){
    $('form.apply-form').request('onLoad', { 
        success: function(data){
            setCookie(data.result);
            console.log(data.result);
            if(data.result == 1 || data.result == undefined || data.result == NaN){
                $('.q-1').modal('show');
            }else{
                let id = parseInt(data.result) + 1;
                console.log(id);
                $('#q-modal-'+ id ).modal('show');
            }
        },
    });
    
});

//switch to next modal
$('body').on('click','.btn-next',function(){
    let next = $(this).data('next');
    let previous = next-1;
    //console.log(previous); 
    var isValid = true;

    isValid = checkIsValid('#q-modal-'+previous);


    if($(this).closest('.q-modal').hasClass('q-1')){
        let checkboxes = $('.q-1').find('input[type=checkbox]:checked');
        console.log(checkboxes);
        if(checkboxes.length < 3) {
            alert('Minimum number of questions are 3');
            isValid = false;
        }
    }

    if(isValid){
        setCookie(previous);
        $('#q-modal-'+previous).modal('hide');
        if($(this).closest('.q-modal').hasClass('q-last')){
            $('#q-modal-finish').modal('show');
        }
        else{
            $('#q-modal-'+next).modal('show');
        }
        //getVideosThumbs();  
    }
    else{
        alert('Please answer on question.');  
    }  
});
 
$('body').on('click','.btn-finish',function(){
    $('#q-modal-finish').modal('hide');
    $('.do-test').addClass('done');
});

$('body').on('change','#position-picker',function(){
    
    let desc = '.desc-'+$(this).find(':selected').data('to');

    $('.position-description li').hide();
    $(desc).show();
});
$(document).ready(function()
{
    $('img,video').bind('contextmenu', function(e){
        return false;
    }); 
});

function checkIsValid(element){
    let isValid = false;
    $(element).find("input,textarea").each(function() {
       var element = $(this);
       console.log(element);
       if( element.is('input') ){
        if( element.attr('type') == 'checkbox' || element.attr('type') == 'radio' ) {
            if( $(this).is(':checked') ) {
                isValid = true;
            }
        }
        else if(element.attr('type') == 'text'){
            if( $(this).val() != "" ){
                isValid = true;
            }
        }
       }
       else if( element.is('textarea') ){
            if( $(this).val() != "" ){
                isValid = true;
            }
       }
    });

    return isValid;
}

function setCookie(num){
$('form.apply-form').request('onQuestionChange', { 
    data: {q_num: num},
    success: function(data){

        //$('.filterPosts').html(data.posts);

    },
});
}

function getCookie(){
$('form.apply-form').request('getSession', { 
    success: function(data){
        console.log(data);
    },
});
}



var controls = false;
var clicked = 0;
$('.video-box').click(function(){
    //console.log(clicked);
    //if(clicked == 0){
    let video = $(this).find('.video');
    video.show();
    $(this).find('.playpause').hide();
    $(this).closest('.image-box').addClass('fullSize');
    $(this).addClass('lightBox');
    $(this).closest('.image-box').find('.videoOverlay').fadeIn();
    if(!$(this).hasClass('special-video')){
        $('body').addClass('noScroll');
    }
    else{
        //$(this).attr('disablepictureinpicture','disablepictureinpicture');
        //$(this).attr('controlsList','nodownload');
    }
    //console.log('controls');
    video.get(0).setAttribute("controls","controls");  
 
    if(clicked == 0){ 
        //console.log('test');
        //$(this).closest('.image-box').find('.video').get(0).currentTime = 0;
    }

    controls = true;
    
    if(video.get(0).paused){
        video.get(0).play();
        //$(this).find('.playpause').hide();
    }
    else{
        video.get(0).pause();
        //$(this).find('.playpause').show();
    }
    //}
    clicked ++;
    //console.log('clicked');
});


$('.videoOverlay').click(function(){
    $(this).closest('.image-box').removeClass('fullSize');
    $(this).closest('.image-box').find('.video-box').removeClass('lightBox');
    $(this).closest('.image-box').find('.video').get(0).pause();
    $(this).closest('.image-box').find('.video').hide();
    $(this).fadeOut();
     $(this).closest('.image-box').find('.playpause').show();
    $('body').removeClass('noScroll');
    $(this).closest('.image-box').find('.video').get(0).removeAttribute("controls");
    controls = false;
    //console.log(clicked);
    if(clicked == 0){ 
        //console.log('test');
        //$(this).closest('.image-box').find('.video').get(0).currentTime = 0;
    }
    //clicked = 0;
});

$('.video-box').find('.video').on('ended', function(){
    //$(this).get(0).removeAttribute("controls");
    controls = true;
});


//limit answers to 3
$('.q-first').find('input[type=checkbox]').on('change', function (e) {
    if ($('input[type=checkbox]:checked').length > 3) {
        $(this).prop('checked', false);
        //alert("allowed only 3");
    }
});

/*
 function getVideosThumbs(){
    var videos = document.getElementsByClassName('video');
    //console.log(videos);
    for (let video of videos) {
        ////console.log(video);
        curTime = video.getAttribute('data-time');
        //console.log(curTime);
        //video.currentTime = curTime;
    }    
}*/

