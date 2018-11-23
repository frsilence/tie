@extends('templates/head')
@section('content')
<style>
	a.nowselected{
		color: #FFFFFF !important;
		background-color: #0000C2 !important;
	}
</style>
<div id="app" v-cloak>
        <ul class="pagination">
            <li>
                <a v-if="currentPage == 1" >首页</a>
                <a v-else href="javascript:;" @click="next(1)">首页</a>
            </li>
            <li v-if="currentPage<=1"><a>上一页</a></li>
            <li v-else><a href="javascript:;" @click="next(currentPage-1)">上一页</a></li>


            <li v-for="item in pagingList">
                <a v-if="currentPage==item.key || sign ==item.key" v-bind:class="{ 'nowselected':(item.key==currentPage) }">@{{item.key}}</a>
                <a v-else href="javascript:;" @click="next(item.value)">@{{item.key}}</a>
            </li>

            <li v-if="currentPage>=totalPageCount"><a>下一页</a></li>
            <li v-else><a href="javascript:;" @click="next(currentPage+1)">下一页</a></li>
            <li>
                <a v-if="totalPageCount == currentPage">尾页</a>
                <a v-else href="javascript:;" @click="next(totalPageCount)">尾页</a>
            </li>
        </ul>
        <p>共:@{{totalPageCount||0}}页,当前页为第@{{currentPage||0}}页&nbsp;&nbsp;&nbsp;设置总页数:<input style="width:20px;" v-model="totalPageCount"></p>
        
    </div>
    
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script type="text/javascript">
        var app = new Vue({
          el: '#app',
          data: {
              // 省略的符号
              sign:'...',
              // 省略号位置
              signIndex:9,
              // 总页数
              totalPageCount: 11,
              // 当前页
              currentPage:1,
              // 显示在页面的数组列表
              pagingList:[]
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
          methods: {
              // 跳转到某页码
              next (num) {
                  var that = this
                  if (num <= 1) that.currentPage = 1;
                  else if (num >= that.totalPageCount) that.currentPage = that.totalPageCount;
                  else that.currentPage = num;
              },
              // 初始化数据
              fetchData () {
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
              init () {
                  var that = this

                  that.fetchData()
              }
          },
          mounted () {
              var that = this

              that.init()
          }
        })    
    </script>

@endsection