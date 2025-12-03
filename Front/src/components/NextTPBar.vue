<template>
  <v-card
    class="d-flex w-100 pa-4 justify-center align-center"
    style="height: 50px"
    color="primary"
  >
    <span class="white--text text-h6">
      Далее по т/п
      <span :class="{ change: changed }">
        {{ nextProcess }}
      </span>
    </span>
  </v-card>
</template>

<script>
export default {
  name: "NextTPBar",
  props: {
    nextProcess: String,
    changed: Boolean
  }
};
</script>

<style scoped>
.change {
  color: #ffee58 !important;
}
</style>
