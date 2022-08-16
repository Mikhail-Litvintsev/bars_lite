<template>
    <button class="red-button">
        <slot/>
    </button>
</template>

<script>
export default {
    name: "ButtonRed"
}
</script>

<style scoped lang="scss">
.red-button {
    -webkit-transition-duration: 0.4s;
    transition-duration: 0.4s;
    border: 2px solid #a52834;
    border-radius: 12px;
    min-width: 100px;
    text-align: center;
    vertical-align: center;
}

.red-button:hover {
    background-color: #a52834;
    color: white;
    border: 2px solid #a52834;
    border-radius: 12px;
    min-width: 100px;
    text-align: center;
    vertical-align: center;
}
</style>
