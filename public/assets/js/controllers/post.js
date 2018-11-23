var posts = new Vue({
	el:'#post',
	data:{
		forum:{forum_id:null,forum_name:null,forum_description:null,forum_sort:null,posts:null},
		select_post:{},
		comments:null,
		save_comment:{'content':'','post_id':''},
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
              totalPageCount (val) {
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
		this.getComment(1);
		this.init()
		
	},
	computed:{
		getPostUrl:function(){
			var host = location.host;
			return host+'/post/';
		}
	},
	methods:{
		deletePost:function(post){
			if(confirm('该操作不可逆！\n确认删除文章：'+post.title+'?')){
				$.ajax({
					type: "post",
					url: "/api/post/deletepostbypostid/" + post.id,
					async: true,
					data: {
						_method: 'delete',
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
					},
					success: function(data) {
						if(data.status == 200) {
							alert('删除成功');
							window.location.href="/forum/"+post.forum_id;
						} else {
							alert('删除失败');
						};
					},
					error: function(date) {
						alert('删除失败');
					},
				});
			}
		},
		showUpdatePost:function(post){
			$('.update-post').show();
			posts.select_post = post;
		},
		closeUpdatePost:function(){
			$('.update-post').hide();
			posts.select_post={};
		},
		updatePost:function(post){
			posts.select_post['_method']='PUT';
			$.ajax({
				type:"post",
				url:"/api/post/updatepostbypostid/"+post.id,
				async:true,
				data:posts.select_post,
				headers:{
					'X-CSRF-TOKEN':$("meta[name='csrf-token']").attr('content'),
				},
				success:function(data){
					if(data.status==200){
						alert('文章更新成功');
						posts.closeUpdatePost();
						window.location.reload();
					}
					else{
						posts.message.error = data.message;
					}
				},
				error:function(){
					alert('更新出错');
				}
			});
		},
		getComment:function(num){
			var url = window.location.href;
			index = url.indexOf('post/');
			id = url.substring(index+5);
			$.ajax({
				type:"get",
				url:"/api/comment/getcommentbypostid/"+id+'?page='+num,
				async:true,
				dataType:"json",
				success:function(data){
					posts.comments = data.comments;
					posts.totalPageCount = data.comments.last_page
              		posts.currentPage = data.comments.current_page
				}
			});
		},
		getCommentsList:function(){
			var comment_list =[];
			var host = location.host;
			for(var i=0;i<this.comments.data.length;i++)
			{
				comment_list.push({'user_name':this.comments.data[i].user_name,
								'content':this.comments.data[i].content,
								  'floor':this.comments.data[i].floor,
								  'time':this.comments.data[i].created_at});
			}			
			return comment_list;
		},
		showPopup:function(){
			this.message.success="";
			this.message.error=[];
			$('.new-comment').show();
		},
		closePopup:function(){
			this.save_comment.content="";
			this.message.success="";
			this.message.error=[];
			$('.new-comment').hide();
		},
		//提交回复
		createComment:function(){
			posts.message.success="";
			posts.message.error=[];
			var url = window.location.href;
			index = url.indexOf('post/');
			id = url.substring(index+5);
			this.save_comment.post_id=id;
			$.ajax({
				type:"post",
				url:"/api/comment/addcomment",
				data:{'content':this.save_comment.content,
				'post_id':this.save_comment.post_id},
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				success:function(data){
					if(data.status==200){
						posts.message.success=data.message;
						posts.save_comment.content="";
						posts.getComment(posts.currentPage);
						setTimeout('posts.message.success=""','1000');
					}
					else{
						posts.message.error=data.message;
					}
				},
				error:function(){
					alert("提交出错");
				}
			})
		},
		// 跳转到某页码
              next:function(num) {
                  var that = this
                  if (num <= 1) that.currentPage = 1;
                  else if (num >= that.totalPageCount) that.currentPage = that.totalPageCount;
                  else that.currentPage = num;
                  that.getComment(num);
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