<template>
  <v-card class="w-100 h-100 justify-start align-start d-flex flex-column">
    <v-card-title class="d-flex flex-row w-100">
      <div class="d-flex flex-row w-100 align-center">
        <v-text-field
          v-model="search"
          label="Введите табельный номер для поиска"
          single-line
          hide-details
          class="my-3 lh-0 w-100"
          style="max-width: 500px; min-width: 500px;"
          v-on:keyup.enter="searchLogs()"
        />
        <v-btn tile color="primary" class="ms-3 mt-2" @click="searchLogs()">
          <v-icon left>mdi-magnify</v-icon>Поиск
        </v-btn>
      </div>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="items"
      class="elevation-0 w-100 px-4"
      style="white-space: nowrap"
      :loading="tableLoading"
      loading-text="Загрузка данных..."
      :items-per-page="options.itemsPerPage"
      hide-default-footer
      :page.sync="options.page"
      @page-count="options.pageCount = $event"
    >
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>

    <div
      class="text-center pt-2 px-2 d-flex w-100 align-center justify-space-between"
    >
      <v-pagination
        v-model="options.page"
        :length="options.pageCount"
        :total-visible="7"
      ></v-pagination>
      <v-select
        :items="[5, 10, 15, 30, 'All']"
        :value="options.itemsPerPage"
        label="Строк на странице"
        @change="options.itemsPerPage = parseInt($event, 10)"
        style="max-width: 200px;"
        class="pe-3"
      />
    </div>
  </v-card>
</template>

<script>
import Api from "@/services/api";

export default {
  name: "LogsPage",
  data: () => ({
    tableLoading: false,
    options: {
      page: 1,
      itemsPerPage: 30,
      pageCount: 1
    },
    search: "",
    headers: [
      { text: "Дата", align: "start", sortable: true, value: "date" },
      { text: "Время", value: "time" },
      { text: "Действие", value: "action" }
    ],
    items: []
  }),
  methods: {
    async searchLogs() {
      try {
        this.tableLoading = true;
        const response = await Api.fetchLogsByTabNum(this.search);
        if (response.data != "not found") {
          this.items = response.data;
        }
      } catch (error) {
        this.$root.snackbar.showError(
          `Ошибка выполнения операции\nОбратитесь к разработчику или попробуйте еще раз`
        );
      } finally {
        this.tableLoading = false;
      }
    }
  }
};
</script>
