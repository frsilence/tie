var sorts = new Vue({
	el:'#sorts',
	data:{
		 forums:[],
	},
	mounted:function(){
		this.getForum();
	},
	methods:{
		getForum:function()
		{
			var url = window.location.href;
			index = url.indexOf('sort/');
			id = url.substring(index+5);
			$.ajax({
				type:"get",
				url:"/api/forum/getforumbysortid/"+id,
				async:true,
				success:function(data){
					sorts.forums = data.forums.data;
				},
				error:function(){
					
				}
			});
		},
		goForum:function(forum_id)
		{
			var host = location.host;
			window.location.href='http://'+host+'/forum/'+forum_id;
		}
	},
})
