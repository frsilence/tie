var forums = new Vue({
	el:'#forum',
	data:{
		forum:{forum_id:null,forum_name:null,forum_description:null,forum_sort:null,posts:null},
		posts:{},
		new_post:{'title':'','content':'','forum_id':''},
		// 省略的符号
		sign: '...',
		// 省略号位置
		signIndex: 9,
		// 总页数
		totalPageCount:1,
		// 当前页
		currentPage:1,
		// 显示在页面的数组列表
		pagingList: [],
		message:{'success':"","error":[]},
	},
	watch: {
              totalPageCount1 (val) {
                  var that = this
                  if (!val || val == '') return;
                  that.currentPage = 1;
                  that.init()
              },
              currentPage (val) {
                  var that = this
                  that.init()
              }
          },
	mounted:function(){
		this.getForum();
		this.getPost(1);
		this.init()
		
	},
	computed:{
		getPostUrl:function(){
			var host = location.host;
			return host+'/post/';
		}
	},
	methods:{
		getForum: function(){
			var url = window.location.href;
			index = url.indexOf('forum/');
			id = url.substring(index+6);
			$.ajax({
				type:"get",
				url:"/api/forum/getbyid/"+id,
				async:true,
				success:function(data){
					forums.forum = data.forum;
				}
			});
		},
		getPost:function(num){
			var url = window.location.href;
			index = url.indexOf('forum/');
			id = url.substring(index+6);
			$.ajax({
				type:"get",
				url:"/api/post/getpostbyforumid/"+id+'?page='+num,
				async:true,
				success:function(data){
					forums.posts=data.posts;
					forums.totalPageCount = data.posts.last_page
              		forums.currentPage = data.posts.current_page
              		forums.init()
				}
			});
		},
		getPostsList:function(){
			var post_list =[];
			var host = location.host;
			for(var i=0;i<this.posts.data.length;i++)
			{
				post_list.push({'title':this.posts.data[i].title,
								'url':'http://'+host+'/post/'+this.posts.data[i].id,
								'user_name':this.posts.data[i].user_name,
								'delete_post':this.posts.data[i].delete_post,
								'post_id':this.posts.data[i].id,
								'created_at':this.posts.data[i].created_at});
								
			}			
			return post_list;
		},
		showPopup:function(){
			this.message.success="";
			this.message.error=[];
			$('.new-post').show();
		},
		closePopup:function(){
			this.message.success="";
			this.message.error=[];
			$('.new-post').hide();
		},
		createComment:function(){
			forums.message.success="";
			forums.message.error=[];
			var url = window.location.href;
			index = url.indexOf('forum/');
			id = url.substring(index+6);
			this.new_post.forum_id=id;
			$.ajax({
				type:"post",
				url:"/api/post/addpost",
				data:{'title':this.new_post.title,'content':this.new_post.content,
				'forum_id':this.new_post.forum_id},
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				success:function(data){
					if(data.status==200){
						forums.message.success=data.message;
						forums.new_post.content="";
						forums.new_post.title="";
						forums.getPost(forums.currentPage);
						setTimeout('forums.message.success=""','1000');
						window.location.href='http://'+location.host+'/post/'+data.post_id;
					}
					else if(data.status==401){
						alert('当前登录账号无权限发表文章');
						forums.closePopup();
					}
					{
						forums.message.error=data.message;
					}
				},
				error:function(){
					alert("提交出错");
				}
			})
		},
		deletePost:function(post_id){
			alert(post_id);
			$.ajax({
				type:"post",
				url:"/api/post/deletepostbypostid/"+post_id,
				async:true,
				data:{
					_method:'delete',
				},
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				success:function(data){
					if(data.status==200){
						alert('delete success');
					}
					else{
						alert('fail');
					};
				},
				error:function(date){
					alert('error');
				}
			});
		},
		// 跳转到某页码
              next:function(num) {
                  var that = this
                  if (num <= 1) that.currentPage = 1;
                  else if (num >= that.totalPageCount) that.currentPage = that.totalPageCount;
                  else that.currentPage = num;
                  that.getPost(num);
              },
              // 初始化数据
              fetchData:function() {
                  var that = this
                  that.pagingList = [];
                  var tmp = null;                  
                  if ((that.totalPageCount) > 10) {
                      if (((that.totalPageCount-1) == (that.totalPageCount - that.currentPage)) && (that.totalPageCount - that.currentPage) > 6) {
                          for (var i=1;i<11;i++) {
                              if (i < that.signIndex) {
                                  tmp = {key:i, value:i }
                              } else if (i== that.signIndex) {
                                  tmp = {key:that.sign, value:0 }
                              } else if (i == (that.signIndex + 1) ) {
                                  tmp = {key:that.totalPageCount - 1, value:that.totalPageCount - 1 }
                              } else {
                                  tmp = {key:that.totalPageCount, value:that.totalPageCount }
                              }
                              that.pagingList.push(tmp)
                          }
                      } else if (((that.totalPageCount - that.currentPage) <= that.signIndex)){
                          var starNum = that.totalPageCount - 9;
                          for (var i=starNum;i<starNum+10;i++) {
                              tmp = {key:i, value:i }
                              that.pagingList.push(tmp)
                          }
                      } else {
                          var starNum = that.currentPage - 1;
                          for (var i=1;i<11;i++) {
                              if (i < that.signIndex) {
                                  tmp = {key:(starNum - 1) + i, value:(starNum - 1) + i }
                              } else if (i== that.signIndex) {
                                  tmp = {key:that.sign, value:0 }
                              } else if (i == (that.signIndex + 1) ) {
                                  tmp = {key:that.totalPageCount - 1, value:that.totalPageCount - 1 }
                              } else {
                                  tmp = {key:that.totalPageCount, value:that.totalPageCount }
                              }
                              that.pagingList.push(tmp)
                          }
                      }
                  } else {
                      for (var i =0; i <that.totalPageCount; i++) {
                          tmp = {key:i+1, value:i+1 }
                          that.pagingList.push(tmp)
                      }
                  }
              },
              init:function(){
                  var that = this;
                  that.fetchData();                  
              }
          }
})