<template>
    <i 
      :class="iconClass"
      data-bs-toggle="tooltip"
      :data-bs-placement="placement"
      :data-bs-custom-class="customClass"
      :title="title"
    ></i>
</template>
  
<script>
    import { onMounted } from 'vue';
    import { Tooltip } from 'bootstrap'
  
    export default {
        name: 'Tooltip',
        props: {
            iconClass: {
                type: String,
                default: 'bi-info-circle'
            },
            title: {
                type: String,
                required: true
            },
            placement: {
                type: String,
                default: 'right'
            },
            customClass: {
                type: String,
                default: 'custom-tooltip'
            }
        },
        setup() {
            onMounted(() => {
                new Tooltip(document.body, {
                selector: "[data-bs-toggle='tooltip']",
                })
            });
        }
    }
</script>
  
<style scoped>

</style>
  