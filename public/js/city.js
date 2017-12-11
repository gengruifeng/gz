// /**
//  * Created by ouryuuyou on 16/9/19.
//  */
$(document).ready(function() {
    $('#city').find('option').eq(0).prop('selected',true);
    var val = $("#province  option:selected").attr('data-text');
    $.ajax({
        url:"/ajax/city", //你的路由地址
        type:"post",
        dataType:"json",
        data:{pid:val,_token:$("input[name = '_token']").val()},
        success:function(data){

            var count = data.length;
            var cityname = $("#cityname").val();
            var b="";
            for(i=0;i<count;i++){
                if(cityname !== data[i].areaname){
                    b+="<option value='"+data[i].areaname+"'>"+data[i].areaname+"</option>";
                }
            }
            $("#city").append(b);

        }
    });
})
function changeCity() {
    $('#city').find('option').eq(0).prop('selected',true);
    var val = $("#province  option:selected").attr('data-text');
    $.ajax({
        url:"/ajax/city", //你的路由地址
        type:"post",
        dataType:"json",
        data:{pid:val,_token:$("input[name = '_token']").val()},
        success:function(data){

            $("#city").empty();
            var count = data.length;
            var b="<option class='city_se' value=''>请选择城市</option>";
            for(i=0;i<count;i++){
                b+="<option value='"+data[i].areaname+"'>"+data[i].areaname+"</option>";
            }
            $("#city").append(b);

        }
    });

}