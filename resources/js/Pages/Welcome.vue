<script setup>
import { ref, watch } from 'vue';
import html2canvas from 'html2canvas';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

let imgUrl = ref(null);
const screenshot = () => {
    html2canvas(document.body).then(function(canvas) {
        // console.log('canvas file', canvas)
        var base64URL = canvas.toDataURL('image/jpeg').replace('image/jpeg', 'image/octet-stream');
        // console.log('base64URL', base64URL)
        // axios.post('/api/screenshot', {
        //     image: base64URL,
        // }).then(response => {
        //     console.log(response.data)
        // })

        document.body.appendChild(canvas);
        imgUrl = base64URL;
        viewScreenshotModal()
    });
}

const clickStart = () => {
    timerEnable.value = true;
    count.value = 3;
}

const screenshot2 = () => {

    let formdata = new FormData();
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

        html2canvas(document.getElementById('images-here')).then(function(canvas) {
        document.body.appendChild(canvas);

        var base64URL = canvas.toDataURL('image/jpeg').replace('image/jpeg', 'image/octet-stream');
        formdata.append('image', base64URL);


    axios.post(route('screenshot.store'), formdata, config)
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
        });
}

let count = ref(0);
let timerEnable = ref(false);

let visible = ref(false);
let viewScreenshotModal = () => {
    visible.value = true;
};

const closeModal = () => {
    visible.value = false;
}

watch(
    count,
    data => {
        setTimeout(() => {
            if(data === 0){
                timerEnable.value = false;
                screenshot();
            }

            if(timerEnable.value && data > 0){
                count.value = data - 1;
            }
        }, 1000);
    },
)



</script>
<template>
    <div class="p-24">
        <button @click="clickStart" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            Take screenshot whole page
        </button>

        <h1 class="text-lg">
            Upload countdown: {{ count }}
        </h1>
        <br />
        <!-- <button @click="screenshot2" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Take screenshot on div only
        </button> -->
    </div>

    <div id="images-here" class="border-2 m-10">
        Screen shot will be viewed below this div
    </div>


    <Modal :show="visible" @close="visible = false" maxWidth="2x1" class="m-4">
        <div class="mt-6 flex justify-end p-4">
            <SecondaryButton @click="closeModal"> Close </SecondaryButton>
        </div>
        <img :src="imgUrl" class="w-90%"> 
    </Modal>
</template>