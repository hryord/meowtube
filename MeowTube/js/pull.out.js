$(function(){	
	$(window).scroll(function(){		
		
		var bottomPos = $(document).height() - $(window).height() -600;
		
		if ($(window).scrollTop() > bottomPos){
			$('#interview').animate({'bottom':'0px'},500);
        
		}else {
		    $('#interview').stop(true).animate({'bottom':'-165px'},200);
		}
    });

		
	//closeをクリックして#interviewを消去
	$('#interview .close').bind('click',function(){
		$('#interview').remove();
	});
});
