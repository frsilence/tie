var user_permission = new Vue({
	el:'#userpermission',
	data:{
		allpermission:'',
		allrole:'',
		selectpermission:'',
		roles:'',
		newrole:{'role_name':'','role_slug':'','role_permission':[]},
		selectrole:'',
		message:{'success':'','error':''},
		search_userparam:"",
		user:'',
		selectuser:'',
		newuser:{'name':'','email':'','password':'','password_confirmation':'','userrole':''},
	},
	mounted:function(){
		this.getRole();
		this.getAllPermission();
	},
	methods:{
		getRole:function(){
			$.ajax({
				type:"get",
				url:"/api/permission/getallrole",
				async:true,
				success:function(data){
					user_permission.roles = data.roles;
				},
				error:function(){
					alert("角色信息获取失败");
				},
			});
		},
		getAllPermission:function(){
			$.ajax({
				type:"get",
				url:"/api/permission/getallpermission",
				async:true,
				success:function(data){
					user_permission.allpermission = data.permissions;
				},
				error:function(data){
					alert('获取所有权限失败');
				}
			});
		},
		deleteRole:function(role){
			$.ajax({
				type:"post",
				url:"/api/permission/deleterole/"+role.id,
				async:true,
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
				},
				data:{
					_method:'DELETE',
				},
				success:function(data){
					if(data.status==200){
						alert('角色:'+role.name+'删除成功');
						user_permission.getRole();
					}
					
				},
				error:function(){
					alert('角色删除失败');
				},
			});
		},
		showUpdateRole:function(role){
			var select_permission = [];
			for (var i=0;i<(role.permissions).length;i++) {
				select_permission.push(role.permissions[i].id);
			}
			this.selectrole={'id':role.id,'name':role.name,'slug':role.slug,'permissions':role.permissions,'role_permission':select_permission}
			$('.update-role').show();
		},
		showNewRole:function(){
			this.newrole = {'role_name':'','role_slug':'','role_permission':[]};
			this.message = {'success':'','error':''};
			$('.new-role').show();
		},
		createNewRole:function(newrole){
			$.ajax({
				type:"post",
				url:"/api/permission/addrole",
				async:true,
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
				},
				data:{
					role_name:user_permission.newrole.role_name,
					role_slug:user_permission.newrole.role_slug,
					role_permission:user_permission.newrole.role_permission,
				},
				success:function(data){
					if(data.status==200){
						user_permission.message.success = data.message;
						user_permission.message.success="";
						user_permission.closeNewRole();
						window.location.reload();
					}
					else{
						user_permission.message.error=data.message;
					}
				},
				error:function(data){
					user_permission.message.error=["创建失败,确认输入信息是否正确"];
					setTimeout('user_permission.message.error=""','2000');
				}
			});
		},
		updateRole:function(selectrole){
			$.ajax({
				type:'post',
				url:'/api/permission/updaterole/'+selectrole.id,
				async:true,
				headers:{
					'X-CSRF-TOKEN':$("meta[name='csrf-token']").attr('content'),
				},
				data:{
					_method:'PUT',
					'permission':user_permission.selectrole.role_permission,
				},
				success:function(data){
					if(data.status==200){
						user_permission.message.success = data.message;
						user_permission.message.success="";
						user_permission.closeUpdateRole();
						user_permission.getRole();
					}
					else{
						user_permission.message.error = data.message;
						setTimeout('user_permission.message.error=""','1000');
						user_permission.closeUpdataRole();
					}
				},
				error:function(){
					user_permission.message.error = ['更新失败'];
				},
			});
		},
		closeNewRole:function(){
			this.newrole = {'role_name':'','role_slug':'','role_permission':[]};
			this.message = {'success':'','error':''};
			$('.new-role').hide();
		},
		closeUpdateRole:function(){
			this.selectrole = "";
			this.message = {'success':'','error':''};
			$('.update-role').hide();
		},
		searchUser:function(user_param){
			user_permission.user = '';
			$.ajax({
				type:"get",
				url:"/api/user/getuser/"+user_param,
				async:true,
				success:function(data){
					user_permission.user = data.user;
				},
				error:function(){
					return;
				},
			});
		},
		deleteUser:function(user){
			if(confirm('确认使该账户失效？')){
				$.ajax({
					type:'post',
					url:'/api/user/changeuseractive/false/'+user.id,
					async:true,
					headers:{
						'X-CSRF-TOKEN':$("meta[name='csrf-token']").attr('content'),
					},
					data:{
						_method:'put',
					},
					success:function(data){
						if(data.status==200){
							user_permission.searchUser(user.id);
						}
						
					},
					error:function(){
						alert('更新失败');
					},
				})
			}
		},
		activeUser:function(user){
			if(confirm('确认使该账户激活？')){
				$.ajax({
					type:'post',
					url:'/api/user/changeuseractive/true/'+user.id,
					async:true,
					headers:{
						'X-CSRF-TOKEN':$("meta[name='csrf-token']").attr('content'),
					},
					data:{
						_method:'put',
					},
					success:function(data){
						if(data.status==200){
							user_permission.searchUser(user.id);
						}
						
					},
					error:function(){
						alert('更新失败');
					},
				})
			}
		},
		showUpdateUserrole:function(user){
			user_permission.message={'success':'','error':''};
			if(user.roles==''){
				user_permission.selectuser = {'name':user.name,'id':user.id,'userrole':[]};
			}
			else{
				user_permission.selectuser = {'name':user.name,'id':user.id,'userrole':user.roles[0].id};
			}
			
			$('.update-userrole').show();
		},
		closeUpdateUserrole:function(user){
			user_permission.message={'success':'','error':''};
			$('.update-userrole').hide();
		},
		updateUserrole:function(selectuser){
			$.ajax({
				type:'post',
				url:'/api/permission/addroletouser/'+selectuser.id+'/'+selectuser.userrole,
				async:true,
				headers:{
					'X-CSRF-TOKEN':$("meta[name='csrf-token']").attr('content'),
				},
				success:function(data){
					if(data.status==200){
						user_permission.message.success = data.message;
						setTimeout('user_permission.message.success=""','1000');
						user_permission.closeUpdateUserrole();
						user_permission.searchUser(selectuser.id);
					}
					else{
						user_permission.message.error = data.message;
						setTimeout('user_permission.message.error=""','1000');
						user_permission.closeUpdateUserrole();
					}
				},
				error:function(){
					user_permission.message.error = ['更新失败'];
				},
			});
		},
		//批量导入用户
		importUser:function(){
			var formData = new FormData();
			formData.append('user_file',$("#import_input")[0].files[0]);
			$.ajax({
				type:"post",
				url:"/api/user/batchimportuser",
				async:true,
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				data:formData,
				cache: false,
                contentType: false, //禁止设置请求类型
                processData: false, //禁止jquery对DAta数据的处理,默认会处理
				success:function(data){
					if(data.status==200){
						alert('本次导入并创建成功'+data.success_count+'个账户，创建失败'+data.fail_count+'个账户');
					}
					else{
						user_permission.message.error = data.message;
						setTimeout('user_permission.message.error=""','2000');
						
					}
				},
				error:function(){
					alert('fail');
				},
			});
		},
		showNewUser:function(){
			this.message = {'success':'','error':''};
			$('.new-user').show();
		},
		closeNewUser:function(){
			this.message = {'success':'','error':''};
			$('.new-user').hide();
		},
		createNewUser:function(newuser){
			$.ajax({
				type:"post",
				url:"/api/user/adduser",
				async:true,
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				data:user_permission.newuser,
				success:function(data){
					if(data.status==200){
						user_permission.message.success = '创建成功！';
						user_permission.closeNewUser();
					}
					else{
						user_permission.message.error = data.message;
					}
				},
				error:function(){
					user_permission.message.error = ['创建失败！'];
				},
			});
		},
	},
})