var user_setting = new Vue({
	el:'#usersetting',
	data:{
		user_inform:{},
	},
	mounted:function(){
		this.getUserSelf();
	},
	methods:{
		getUserSelf:function(){
			$.ajax({
				type:"get",
				url:"/api/user/getuserself",
				async:true,
				success:function(data){
					if(data.status==200){
						user_setting.user_inform = data.userinform;
						user_setting.user_inform['area_p'] = data.userinform.area.split('-')[0];
						user_setting.user_inform['area_c'] = data.userinform.area.split('-')[1];
						user_setting.user_inform['role_name'] = data.userinform.roles[0].name;
						selectprovince1(data.userinform.area.split('-')[0]);
					}
					else{
						user_setting.message_user.error = data.message;
					}
				},
				error:function(){
					alert('获取用户信息错误');
				}
			});
		},
		saveUserInform:function(userinform){
			user_setting.user_inform['_method']='PUT';
			user_setting.user_inform['area']=user_setting.user_inform['area_p']+'-'+user_setting.user_inform['area_c'];
			$.ajax({
				type:"post",
				url:"/api/user/updateuserselfinform",
				async:true,
				data:user_setting.user_inform,
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
				},
				success:function(data){
					if(data.status==200){
						alert('信息保存成功');
						user_setting.getUserSelf();
					}
					else{
						alert('信息保存失败');
					}
				},
				error:function(data){
					alert('信息保存失败');
				}
			});
		},
		updateUserImage:function(){
			var formData = new FormData();
			formData.append('user_image',$("#user_image")[0].files[0]);
			$.ajax({
				type:"post",
				url:"/api/user/updateuserselfimage",
				async:true,
				cache: false,
                contentType: false, //禁止设置请求类型
                processData: false, //禁止jquery对DAta数据的处理,默认会处理
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
				},
				data:formData,		
				success:function(data){
					if(data.status==200){
						alert('用户头像更新成功');
						user_setting.getUserSelf();
					}
					else{
						alert(data.message);
					}
				},
				error:function(data){
					alert('用户头像更新失败');
				}
			})
					
		},
	}
})