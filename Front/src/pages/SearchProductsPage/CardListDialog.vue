<template>
  <v-dialog
    transition="dialog-bottom-transition"
    v-model="localShow"
    max-width="400"
  >
    <v-card>
      <v-toolbar color="primary" dark>
        Созданные КНВ
      </v-toolbar>

      <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-1 w-100 px-0"
        :loading="loading"
        @dblclick:row="(event, { item }) => $emit('edit-card', item)"
        loading-text="Загрузка данных..."
        hide-default-footer
        :page.sync="page"
        @page-count="pageCount = $event"
        dense
      >
        <template v-slot:item.actions="{ item }">
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="primary"
                class="mr-2"
                v-bind="attrs"
                v-on="on"
                small
                @click="$emit('edit-card', item)"
              >
                mdi-pencil
              </v-icon>
            </template>
            <span>Редактировать КНВ</span>
          </v-tooltip>
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="secondary"
                class="mr-2"
                v-bind="attrs"
                v-on="on"
                small
                @click="$emit('copy-card', item)"
              >
                mdi-content-copy
              </v-icon>
            </template>
            <span>Копировать КНВ</span>
          </v-tooltip>
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                small
                disabled
                @click="$emit('delete-card', item)"
              >
                mdi-delete
              </v-icon>
            </template>
            <span>Удалить КНВ</span>
          </v-tooltip>
        </template>
      </v-data-table>
      <v-pagination v-model="page" :length="pageCount" :total-visible="5" />
      <v-row class="pa-3 mx-0 w-100">
        <v-btn text @click="localShow = false">
          Закрыть
        </v-btn>
        <v-spacer />
        <v-btn color="primary" @click="$emit('add-card')">
          Добавить КНВ
        </v-btn>
      </v-row>
    </v-card>
  </v-dialog>
</template>

<script>
import Api from "@/services/api";
export default {
  name: "CardListDialog",
  props: {
    show: Boolean,
    item: Object
  },
  watch: {
    show(newValue) {
      if (newValue) {
        this.showCards();
        this.localShow = newValue;
      }
    },
    localShow(newVal) {
      if (newVal) this.$emit("close");
    }
  },
  data() {
    return {
      items: [],
      localShow: this.show,
      headers: [
        {
          text: "Цех",
          align: "center",
          value: "workshop",
          sortable: true
        },
        {
          text: "ТП",
          align: "center",
          value: "cipher_main_td",
          sortable: true
        },
        {
          text: "Действия",
          align: "center",
          value: "actions",
          sortable: false
        }
      ],
      loading: false,
      page: 1,
      pageCount: 1
    };
  },
  methods: {
    async showCards() {
      try {
        this.items = [];
        this.loading = true;
        const { data } = await Api.fetchProductCards(this.item);
        this.items = data;
        this.$root.logger.log(
          `Просмотрены все созданные КНВ для изделия: ${this.item.p006} | Найдено КНВ: ${data.length}`
        );
      } catch (error) {
        this.$root.snackbar.showError(
          `Ошибка выполнения операции\nОбратитесь к разработчику или попробуйте еще раз`
        );
        this.$root.logger.log(
          `Во время загрузки созданных КНВ для изделия ${this.item.p006} произошла ошибка: ${error}`
        );
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
