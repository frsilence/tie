var head = new Vue({
	el:'#head',
	data:{
		sorts:[]
	},
	mounted:function(){
		this.getSort();
		this.test();
	},
	methods:{
		getSort:function(){
			$.ajax({
				type:"get",
				url:"/api/sort/getallsort",
				async:true,
				success:function(data){
					head.sorts = data.sorts;
				},
				error:function(){
					return;
				}
			});
		},
		getSortUrl:function(sort_id){
			var host = location.host;
			return '/sort/'+sort_id;
		},
		test:function(){
			$.ajax({
				type:'get',
				url:'test1105',
				success:function(data){
					//alert(data);
				}
			})
		}
	}
})