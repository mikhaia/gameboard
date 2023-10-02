<script>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import Toast from '../Inc/Toast.vue';
  let data = ref();
  let title = ref();
  let form = ref();
  let errors = ref();
  let positions = ref();
  const focus = ref();
  const page = usePage();
  export default {
    name: 'cardModal',
    setup() {
      return {data, title};
    },
    methods: {
      open(value) {
        const formData = {
          link: value.link,
          title: value.title,
          cover: value.cover,
          position: value.position,
          progress: value.progress,
          todo: value.todo,
          description: value.description,
          column_id: value.column_id
        };
        if (value.id) {
          formData._method = 'PUT';
        }

        // TODO: Add positions by Drag'n'Drop
        positions = page.props.columns;

        form = useForm(formData);
        data.value = value;
        title.value = value.title;
        setTimeout(() => focus.value.focus(), 100);
      },
      close() {
        data.value = null;
      },
      submit() {
        let url = '/cards';
        if (data.value.id) url += '/' + data.value.id;
        form.todo = document.getElementById('todo').value;
        form.post(url, {
            forceFormData: true,
            onError: (err) => {
              errors.value = err.msg;
            },
            onSuccess: () => {
              Toast.show('Card saved successfully', 'success');
              this.close();
            }
          })
      },
      image(event) {
        changeCover(event.target.files);
      },
      changeLink(event) {
        const url = event.target.value;
        if (url.startsWith('http://') || url.startsWith('https://')) {
          axios('/geturldata', {
            params: { url }
          }).then(function(response) {
            const html = document.createElement('html');
            html.innerHTML = response.data;
            if (!form.title)
              form.title = getMetaData(html, 'title');
            if (!form.description)
              form.description = getMetaData(html, 'description');
            if (!form.cover)
              form.cover = getMetaData(html, 'image');
          });
        }
      }
    }
  };

function getMetaData(html, prop) {
  let result;
  if (prop === 'title')
    result = html.querySelector('title').innerText;
  if (prop === 'description')
    result = html.querySelector('meta[name="description"]').getAttribute('content');

  if (!result)
    result = html.querySelector('meta[name="twitter:'+prop+'"]').getAttribute('content');
  if (!result)
    result = html.querySelector('meta[property="og:image"]').getAttribute('content');

  return result;
}

function textareaKeys(e) {
  const target = e.target;
  if(e.keyCode === 9) {
    target.setRangeText('\t', target.selectionStart, target.selectionStart, 'end');
    e.preventDefault();
  }
}

function changeCover(files) {
  const file = files[0];
  if (!file) return;
  if (file.type === 'image/png' || file.type === 'image/jpeg') {
    form.cover = file;
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
      document.getElementById('cover').previousSibling.src = reader.result;
    }
  }
}

function getImage(url) {
  return '/getimage?url=' + url;
}

window.addEventListener('paste', event => {
  if (data.value) {
    const url = event.clipboardData.getData("text");
    if (url.startsWith('http://') || url.startsWith('https://')) {
      const img = getImage(url);
      axios(img).then(function(response) {
        if (response.data) {
          form.cover = img; 
        }
      });

    } else if (event.clipboardData.files) {
      changeCover(event.clipboardData.files);
    }
  }
})
</script>

<script setup>
const todoExample = 
`Example:
- An undone task
+ Finished group
  + Finished item
`;
// 
</script>

<template>
  <div v-if="data" class="modal">
    <div class="glass container card-container">
      <div class="modal-header">
        <a class="btn-close" @click="close()"></a>
        <h4 class="title">{{ title }}</h4>
      </div>
      <div class="modal-content">
        <form class="form flex gap-5" @submit.prevent="submit">
          <div class="flex-1">
          <div class="form-input pt-5 pb-2.5">
            <input type="text" v-model="form.link" id="link" placeholder="Link" @change="changeLink($event)">
            <label for="link">Link (Will try to fill the empty fields)</label>
          </div>
          <div class="form-input pt-5 pb-2.5">
            <input type="text" v-model="form.title" id="title" placeholder="Title" ref="focus">
            <label for="title">Title</label>
          </div>
          <div class="form-input form-textarea pt-5">
            <textarea type="text" v-model="form.description" id="description" placeholder="Description" @keydown="textareaKeys($event)"></textarea>
            <label for="description">Description</label>
          </div>
          <div class="form-file pt-2.5">
              <label for="cover">
                Cover (Click or Ctrl+V to change)
                <img v-bind:src="form.cover">
                <input type="file"
                  id="cover"
                  @input="form.cover = $event.target.files[0]"
                  @change="image($event)">
              </label>
          </div>
        </div>
        <div class="flex-1">
          <div class="flex flex-col h-full gap-3">
            <div class="form-textarea form-todo flex-1">
              <label for="todo" class="leading-5">Todo:</label>
              <textarea v-model="form.todo" id="todo" :placeholder="todoExample" @keydown="textareaKeys($event)"></textarea>
            </div>
            <div>
              <button type="submit" class="button w-full">Save</button>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</template>
