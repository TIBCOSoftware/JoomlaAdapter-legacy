(function($){
  $(function(){
    $("ul#myTab a,ul#product").live("click",function(){
        if($(this).parents("li").hasClass("active")) return false;
        $(this).parents("li").addClass("active").siblings().removeClass("active");
        var sel = $(this).attr("href");
        $(sel).show().siblings().hide();
        return false;
    });
    $("ul#product").parents("div").find("#overview").addClass("active");
    $("input#login-action-button").live("click",function(){
      var login_form_show = $("#login-form:visible").length;
      if(login_form_show){
        $("#login-form").slideUp();
      }else{
        $("#login-form").slideDown();
      }
      return false;
    });

    $("#login-form").live("submit",loginBackend);
    $("#login-page-login-form").live("submit",loginBackend)
    function loginBackend(){
      if($(".logout-button").length){return true;}
      
      $that     = $(this);
      $id       = $that.attr("id");
      $username = $that.find("input[name='username']").val();
      $passwd   = $that.find("input[name='password']").val();
      $.get(GLOBAL_CONTEXT_PATH + "administrator/index.php",function(res){
        var data = {};
        var rform = $(res).find("#form-login");
            data[$("input[value='1']",rform).attr("name")] = 1;
            data.username = $username;
            data.passwd = $passwd;
            data.option = $("input[name='option']",rform).val();
            data.task = $("input[name='task']",rform).val();

        $.post(GLOBAL_CONTEXT_PATH + "administrator/index.php",data,function(d){
            $("#" + $id).die("submit").submit();
        });
      });

      return false;
    }
  });
})(jQuery);