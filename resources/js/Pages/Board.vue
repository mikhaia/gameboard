<script setup>
import Layout from './Layout.vue'
import cardModal from './Modals/Card.vue';
import axios from 'axios'
import { Head } from '@inertiajs/vue3'
import showdown from 'showdown';
import { ref } from 'vue';

import columnModal from './Modals/Column.vue';
const props = defineProps({ board: Object, columns: Object })

const openModal = (data) => {
  columnModal.methods.open(data)
}

const openCardModal = (data) => {
    cardModal.methods.open(data);
}

const converter = new showdown.Converter();
converter.setOption('simpleLineBreaks', true);

function toHtml(text) {
  text = converter.makeHtml(text);
  text = text.replace(/\[ \]/g, '<input type="checkbox" onclick="event.stopPropagation()">');
  text = text.replace(/\[x\]/g, '<input type="checkbox" checked onclick="event.stopPropagation()">');
  return text;
}

function toTodo(text) {
  const list = text.match(/[^\r\n]+/g);
  const todo = [];

  list.forEach(item => {
    let trimed = item.trim();
    // Fix missing minus sign
    if (trimed.charAt(0) !== '-' && trimed.charAt(0) !== '+') {
      trimed = '- ' + trimed;
    }

    // Getting level of tabs
    let lvl;
    for(lvl = 0; lvl < item.length; lvl++) {
      if (item[lvl] !== '\t') break;
    }

    const line = {
      done: trimed[0] === '+',
      text: trimed.substring(1).trim(),
      lvl: lvl
    };

    todo.push(`<div>${'<i class="mx-2"></i>'.repeat(line.lvl)}<label class="cursor-pointer"><input type="checkbox" ${line.done?'checked':''}> ${line.text}</label></div>`);
  });
  return todo.join('');
}

function stop(event) {
  event.stopPropagation();
}

function changeTodo(event, id) {
  const items = event.currentTarget.children;
  const todo = [];
  for(let i=0; i < items.length; i++) {
    const isChecked = items[i].querySelector('input').checked;
    const text = items[i].querySelector('label').innerText.trim();
    const lvl = items[i].querySelectorAll('i.mx-2').length;
    todo.push(`${'\t'.repeat(lvl)}${isChecked?'+':'-'} ${text}`);
  }
  const result = todo.join("\n");
  axios.put('/cards/todo/' + id, {'todo': result});
  return result;
}

/* Drag'n'Drop */
// TODO: Refactoring Drag'n'Drop
let isDragCard;
document.addEventListener("dragstart", function(event) {
  isDragCard = event.target.classList.contains('card');

  if (isDragCard)
    event.dataTransfer.setData("Text", event.target.closest('.card').id);
  else
    event.dataTransfer.setData("Text", event.target.closest('.drag').id);

  event.target.classList.add('moving');
});

document.addEventListener("drag", function(event) {
  
});

document.addEventListener("dragend", function(event) {
    event.target.classList.remove('moving');
});

document.addEventListener("dragenter", function(event) {
  const droptarget = isDragCard ? 'card-droptarget' : 'droptarget';
    if ( event.target.classList.contains(droptarget) ) {
        event.target.classList.add('active');
    }
});

document.addEventListener("dragover", function(event) {
    event.preventDefault();
});

document.addEventListener("dragleave", function(event) {
  const droptarget = isDragCard ? 'card-droptarget' : 'droptarget';
    if ( event.target.classList.contains(droptarget) ) {
      event.target.classList.remove('active');
    }
});

document.addEventListener("drop", function(event) {
    event.preventDefault();
    const droptarget = isDragCard ? 'card-droptarget' : 'droptarget';
    if ( event.target.classList.contains(droptarget) ) {
      if (isDragCard) {
        const card = document.getElementById(event.dataTransfer.getData("Text"));
        const drop = card.nextSibling;
        event.target.after(card);
        card.after(drop);

        const order = [];
        const columnId = card.closest('.drag').getAttribute('id');
        const cardId = card.getAttribute('id').substring(5);
        const cards = card.closest('.card-container').children;
        for (let i = 0; i < cards.length; i++)
          if (cards[i].classList.contains('card'))
            order.push(cards[i].id.substring(5));

        axios.put('/cards/sort/'+columnId+'/'+cardId, order);
        event.target.classList.remove('active');
      } else {
        const parent = event.target.closest('.drag-container');
        parent.insertBefore(document.getElementById(event.dataTransfer.getData("Text")), event.target.closest('.drag').nextSibling)
        
        event.target.classList.remove('active');
        const order = [];
        for (var i = 0; i < parent.children.length; i++) {
          if (parent.children[i].classList.contains('drag'))
            order.push(parent.children[i].id);
        }

        axios.put('/columns/sort/'+props.board.id, order);
      }
    }
});

let isLight = ref(props.board.dark);
function switchMode() {
  isLight.value = !isLight.value;
  axios.put('/boards/switch/'+props.board.id, {'dark': isLight.value});
}
</script>

<template>
  <Layout>
    <Head :title="'Board / ' + board.title" />
    <div class="board" :class="isLight ? 'mode-light' : 'mode-dark'"
        :style="[board?.background ? { backgroundImage: 'url('+board?.background+')'} : {}]">
        <div class="header">
          <img :src="board?.icon" class="icon">
          <h1>{{ board.title }}</h1>
          <div class="toggle-switch">
            <label>
                <input type="checkbox" @click="switchMode()" :checked="!isLight">
                <span class="slider"></span>
            </label>
          </div>
        </div>
        <div class="columns drag-container">
          <!-- TODO: Add droptaget to fisrt place <div class="droptarget"></div> -->
          <div v-for="column in columns" :id="column.id" class="drag">
            <div class="glass column">
                <h4 class="text-lg font-bold px-2 py-1" :class="{'text-white': board.dark }" draggable="true">
                  <a class="float-right cursor-pointer" @click="openModal(column)">⚙️</a>
                  {{ column.title }}
                </h4>
                <div class="card-container">
                  <template v-for="card in column.cards">
                    <!-- <Card :card="card"></Card> -->
                    <div class="card cursor-pointer shadow-md drag-card" @click="openCardModal(card)" draggable="true" :id="'card-'+card.id">
                        <img :src="card.cover" draggable="false"/>
                        <h6 class="px-2 py-1 title" :class="{'top-title': card.description || card.todo }">{{ card.title }}</h6>
                        <div v-if="card.description" class="description" v-html="toHtml(card.description)"></div>
                        <div v-if="card.todo"
                          class="checklist p-2 cursor-default"
                          v-html="toTodo(card.todo)"
                          @click="stop($event)"
                          @change="card.todo = changeTodo($event, card.id)"></div>
                    </div>
                    <div class="card-droptarget"></div>
                  </template>
                </div>
                <a class="btn-create glass" @click="openCardModal({title: '', column_id: column.id})">Create new</a>
            </div>
            <div class="droptarget"></div>
          </div>
            <div class="glass column add" @click="openModal({title: 'New column', board_id: board.id})"></div>
        </div>
      </div>
      <columnModal></columnModal>
      <cardModal></cardModal>
  </Layout>
</template>