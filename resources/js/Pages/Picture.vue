<script setup>
import axios from 'axios';
import { ref, watch } from 'vue';
import { WebCamUI } from 'vue-camera-lib'   



let imageUrl = ref(null);
const photoTaken = (data) => {
    clickStart();
    // console.log('image blob: ', data.blob)
    // console.log('image data url', data.image_data_url)
    imageUrl.value = data.image_data_url;

}

const processImage = () => {
    axios.post('/api/webcam', {
        image: imageUrl.value,
    }).then(response => {
        console.log(response.data)
    })
}




let count = ref(0);
let timerEnable = ref(false);

const clickStart = () => {
    timerEnable.value = true;
    count.value = 3;
}

watch(
    count,
    data => {
        setTimeout(() => {
            if(data === 0){
                timerEnable.value = false;
                processImage();
            }

            if(timerEnable.value && data > 0){
                count.value = data - 1;
            }
        }, 1000);
    },
)

const webcam = ref(null);

const stop = () => {
    console.log('webcam', webcam)
    webcam.value.pause();
    webcam.value.stop();
}

</script>
<template>
    <!-- <div class="p-24">
        <button @click="clickStart" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            Take a picture
        </button>
    </div> -->
    <div class="max-w-3xl px-2 mx-auto p-24">
        <WebCamUI ref="webcam" :fullscreenState="false"  @photoTaken="photoTaken" />
        <button @click="stop" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            Stop the live
        </button>
    </div>

    <div class="max-w-3xl px-2 mx-auto text-lg">
        Upload countdown: {{ count }}

        <img :src="imageUrl" class="mt-8 mb-4" v-if="count == 0" />
    </div>

    
</template>