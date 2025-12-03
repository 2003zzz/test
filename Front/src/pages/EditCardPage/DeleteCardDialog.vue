<template>
  <v-dialog :value="value" @input="$emit('input')" persistent max-width="500px">
    <v-card>
      <v-card-title class="headline text-center justify-center">
        Вы действительно хотите удалить данную КНВ?
      </v-card-title>
      <v-card-actions>
        <v-spacer/>
        <v-btn color="blue darken-1" text @click="$emit('input')">
          Отменить
        </v-btn>
        <v-btn color="red darken-1" text @click="$emit('delete')">
          Удалить
        </v-btn>
        <v-spacer/>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DeleteCardDialog',
  props: {
    value: Boolean,
  },
}
</script>