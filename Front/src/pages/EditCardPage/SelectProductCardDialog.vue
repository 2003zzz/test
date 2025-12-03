<template>
  <v-dialog :value="value" @input="$emit('input')" persistent max-width="400">
    <v-card>
      <v-card-title>
        <span class="headline">
          Выбор КНВ
        </span>
      </v-card-title>

      <div class="px-6">
        <v-data-table
          item-key="index"
          :headers="headers"
          :items="cards"
          :footer-props="{
            itemsPerPageOptions: [5, 10, 15]
          }"
          class="elevation-0 w-100 px-4 pt-4"
          dense
        >
          <template v-slot:item.actions="{ item }">
            <v-simple-checkbox
              :value="selectedCardIds.includes(item.id_v01)"
              @input="value => toggleCard(item, value)"
              :ripple="false"
              dense
            />
          </template>
        </v-data-table>
      </div>

      <v-divider />

      <v-card-actions>
        <v-btn text @click="$emit('input')">
          Отмена
        </v-btn>
        <v-spacer />
        <v-btn color="primary" @click="confirmSelectedCardIds()">
          Выбор
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "SelectProductCardDialog",
  props: {
    value: Boolean,
    cards: Array,
    selection: Array
  },
  data: () => ({
    selectedCards: [],
    headers: [
      {
        text: "",
        align: "center",
        value: "actions",
        sortable: false
      },
      {
        text: "Цех",
        align: "center",
        value: "workshop",
        visible: true
      },
      {
        text: "ТД",
        align: "center",
        value: "cipher_main_td",
        visible: true
      }
    ]
  }),
  computed: {
    selectedCardIds() {
      return this.selectedCards.map(card => card.id_v01);
    }
  },
  watch: {
    value: {
      handler(newValue) {
        if (newValue) {
          const selected = this.cards.filter(card =>
            this.selection.includes(card.id_v01)
          );
          this.selectedCards = [...selected];
        } else {
          this.selectedCards = [];
        }
      }
    }
  },
  methods: {
    toggleCard(item, value) {
      if (value) {
        this.selectedCards.push(item);
      } else {
        const index = this.selectedCards.findIndex(
          card => card.id_v01 === item.id_v01
        );
        if (index >= 0) this.selectedCards.splice(index, 1);
      }
    },
    confirmSelectedCardIds() {
      this.$emit("select", this.selectedCardIds);
      this.$emit("input");
    }
  }
};
</script>
