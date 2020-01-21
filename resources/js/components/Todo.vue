<template>
<div class="">
    <section class="todoapp">
      <header class="header">
        <h1>Todos</h1>
        <input class="new-todo"
          autofocus autocomplete="off"
          placeholder="What needs to be done?"
          v-model="newTodo"
          @keyup.enter="addTodo()">
      </header>
      <section class="main" v-show="todos.length" v-cloak>
        <input id="toggle-all" class="toggle-all" type="checkbox">
        <label for="toggle-all"></label>
        <ul class="todo-list">
          <li v-for="todo in todos"
            class="todo"
            :key="todo.id"
            :class="{ completed: todo.is_complete, editing: todo == editedTodo }">
            <div class="view">
              <input class="toggle" type="checkbox" v-model="todo.is_complete" v-on:change="activeTodoNow(todo.id,todo.is_complete)" >
              <label @dblclick="editTodo(todo)">{{ todo.name }}</label>
              <button class="destroy" @click="deleteTodo(todo.id)"></button>
            </div>
            <input class="edit" type="text"
              v-model="name"
              v-todo-focus="todo == editedTodo"
              @blur="doneEdit(todo.id)"
              @keyup.enter="doneEdit(todo.id)"
              @keyup.esc="cancelEdit(todo.id)">
          </li>
        </ul>
      </section>
      <footer class="footer" v-if="hasTodo">
        <span class="todo-count">
          <strong>{{ activeTodo }}</strong>  left
        </span>
        <ul class="filters">
          <li><a href="javascript:void(0)" :class="{ selected: visibility == 'all' }" @click="filterNow('all')">All</a></li>
          <li><a href="javascript:void(0)" :class="{ selected: visibility == 'active' }" @click="filterNow('active')">Active</a></li>
          <li><a href="javascript:void(0)" :class="{ selected: visibility == 'completed' }" @click="filterNow('completed')">Completed</a></li>
        </ul>
        <button class="clear-completed" v-if="hasCompleted" @click="deleteTodo('')">
          Clear completed
        </button>
      </footer>
    </section>
    <p>Larvel 6.x and Vue 2 <a href="https://github.com/code4mk/laravel-vue-todos" target="_blank">@code4mk github</a> </p>
</div>


</template>

<script>
import axios from 'axios'
let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
export default {
    data () {
      return {
        newTodo: '',
        selected: false,
        activeTodo: '',
        todos: [],
        visibility: '',
        editedTodo: null,
        beforeEditCache: '',
        hasTodo: false,
        hasCompleted: false,
        name: ''
      }
  },
  methods: {
    addTodo: function () {
        axios.post('todo/add',{
                name: this.newTodo
        }).then((response)=>{
            this.newTodo = ''
            axios.get('todo/get',{
                params: {
                    type: this.visibility
                }
            }).then((response)=>{
                this.todos = response.data.todo
                if(this.todos.length>0){
                    this.hasTodo = true
                }
                this.activeTodo = response.data.active_now
            })
        })
  },

  deleteTodo: function (e) {
      axios.get('todo/delete',{
          params: {
              type: e !== '' ? 'single' : '',
              id: e
          }
      }).then((response)=>{
          axios.get('todo/get',{
              params: {
                  type: this.visibility
              }
          }).then((response)=>{
              this.todos = response.data.todo
              this.activeTodo = response.data.active_now
              if(response.data.has_completed > 0) {
                  this.hasCompleted = true
              }else{
                  this.hasCompleted = false
              }
              if(response.data.total>0){
                  this.hasTodo = true
              }else{
                  this.hasTodo = false
              }
          })
      })
  },
  activeTodoNow: function(id,e) {

      axios.get('todo/action',{
          params:{
              type: e ? 'complete' : ' ',
              id:id
          }
      }).then((response)=>{
          Fire.$emit('get_data')
      })
  },

  filterNow: function(e){
      this.visibility = e;
      axios.get('todo/get',{
          params:{
              type: this.visibility
          }
      }).then((response)=>{
          if (response.data.todo.length > 0) {
              this.activeTodo = response.data.active_now
          }
          this.todos = response.data.todo
      })
  },
  editTodo: function (todo) {
      this.name = todo.name
      this.editedTodo = todo
  },
  doneEdit: function(id){
      axios.get('todo/update',{
          params:{
              id: id,
              name: this.name
          }
      }).then((response)=>{
          if (!this.editedTodo) {
            return
          }
          this.editedTodo = null
          axios.get('todo/get',{
              params:{
                  type: this.visibility
              }
          }).then((response)=>{
              this.todos = response.data.todo
              this.activeTodo = response.data.active_now
          })
      })
  },
  cancelEdit: function() {
      this.editedTodo = null
  }

},
  created: function () {
     this.visibility = 'all'
    axios.get('todo/get',{
        params:{
            type: 'all'
        }
    }).then((response)=>{
        this.todos = response.data.todo
        this.activeTodo = response.data.active_now
        if(response.data.has_completed > 0) {
            this.hasCompleted = true
        }
        if(this.todos.length>0){
            this.hasTodo = true
        }
    })
    Fire.$on('get_data',()=>{
        axios.get('todo/get',{
            params:{
                type: this.visibility
            }
        }).then((response)=>{
            this.activeTodo = response.data.active_now
            if(response.data.has_completed > 0) {
                this.hasCompleted = true
            }else{
                this.hasCompleted = false
            }
        })
    })


},

directives: {
    'todo-focus': function (el, binding) {
      if (binding.value) {
        el.focus()
      }
    }
  }

}
</script>

<style lang="css" scoped >

</style>
