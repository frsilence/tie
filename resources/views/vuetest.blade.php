<!DOCTYPE html>
<html>
	<head>
		<script src="{{asset('assets/js/vue.js')}}"></script>
		<script src="{{asset('assets/js/jquery.min.js')}}"></script>
		<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
		<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-theme.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}" />
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">
					<p>@{{ message }}</p>
					<input v-model="message" />
					<ul>
						<li v-for="(todo,index) in todos">
							@{{ todo.text }}<button v-bind:class="classs.danger" v-on:click="removeTodo(index,1)">remove</button>
							@{{ index }}
						</li>
					</ul>
					<button v-bind:class='classs.primary' v-on:click="reverseMessage">reverse</button>
					<button v-bind:class='classs.success' v-on:click="addTodo">add</button>
					<button v-bind:class='classs.danger' v-on:click="removeTodo(0,'all')">remove</button>
					<div v-html="raw_html"></div>
				</div>
			</div>
		</div>
	</body>
	<script>
		new Vue({
			el:'.title',
			data:{
				message:'vue',
				myclass:'btn-danger',
				isA: true,
    			isB: false,
				todos:[
					{text:'no.1'},
					{text:'no.2'},
					{text:'no.3'}
				],
				raw_html:"<h3>test raw_html</h3>",
				classs:{
					primary:'btn-primary',
					success:'btn-success',
					danger:'btn-danger'
				}
			},
			methods:{
				reverseMessage:function(){
					this.message = this.message.split('').reverse().join("");
				},
				addTodo:function(){
					var text = this.message.trim()
					if(text){
						this.todos.push({text:text});
					};
				},
				removeTodo:function(index,num){
					if(num=='all'){
						num = this.todos.length;
					}
					
					this.todos.splice(index,num)
				}
				
			}
		})
	</script>
	<script>
		document.write(navigator.userAgent)
	</script>
</html>
