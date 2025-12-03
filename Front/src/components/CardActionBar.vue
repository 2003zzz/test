<template>
  <v-expansion-panels @click.stop>
      <v-expansion-panel @click.stop>
        <v-expansion-panel-header @click.stop>
          Действия
          <span v-if="unsavedChanges" class="red--text ms-3">
            Есть несохраненные изменения
          </span>
        </v-expansion-panel-header>
        <v-expansion-panel-content @click.stop>
          <div class="d-flex flex-wrap pa-3" @click.stop>
            <slot></slot>
          </div>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
export default {
  name: 'CardActionBar',
  props: {
    unsavedChanges: Boolean,
  },
}
</script>