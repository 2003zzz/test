<template>
  <v-dialog :value="value" @input="$emit('input')" persistent max-width="690">
    <v-card>
      <v-card-title class="headline">
        Выход со страницы
      </v-card-title>
      <v-card-text>
        На странице есть несохраненные изменения. При выходе со страницы все несохраненные данные будут потеряны. Вы действительно хотите выйти?
      </v-card-text>
      <v-card-actions>
        <v-btn color="red" text @click="$emit('input')">
          Закрыть
        </v-btn>
        <v-spacer/>
        <v-btn text @click="$emit('exit')">
          Выйти без сохранения
        </v-btn>
        <v-spacer/>
        <v-btn color="primary" text :loading="loading" @click="$emit('save')">
          Сохранить и выйти
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'UnsavedChangesDialog',
  props: {
    value: Boolean,
    loading: Boolean,
  },
}
</script>