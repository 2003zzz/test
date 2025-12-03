<template>
  <div class="v-card w-100 h-100 justify-start align-start d-flex flex-column">
    <PageLoadingDialog v-model="pageLoading" @input="$router.back()" />

    <CardActionBar>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="goEditMode()"
        v-if="['PTT05A', 'PTT05A_1'].includes(user.role)"
      >
        <v-icon class="white--text text-h5">mdi-pencil</v-icon>Редактировать
      </v-btn>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="onGetExcel()"
        :loading="getExcelLoading"
      >
        <v-icon class="white--text text-h5">mdi-file-chart</v-icon>Выгрузить
      </v-btn>
    </CardActionBar>

    <v-simple-table dense class="knv-table w-100" style="max-width: 1200px;">
      <template v-slot:default>
        <tbody>
          <tr>
            <td
              colspan="5"
              class="text-center text-uppercase text-h6"
              style="border-bottom: 1px solid black !important;"
            >
              Винт
            </td>
            <td
              colspan="2"
              class="text-center text-uppercase text-h6"
              style="border-bottom: 1px solid black !important;"
            >
              В95
            </td>
            <td colspan="9" class="text-end text-h6">
              Утверждаю: _____________ А.Г. Казаков
            </td>
          </tr>
          <tr>
            <td style="border-left: 1px solid black !important;">Цех №</td>
            <td colspan="2" class="text-center text-uppercase text-h6">
              {{ main.workshop || "" }}
            </td>
            <td
              colspan="4"
              class="text-center text-uppercase text-subtitle-1 font-weight-bold"
              style="white-space: nowrap; border-right: 1px solid black !important; border-left: 1px solid black !important;"
            >
              Карточка норм времени
            </td>
            <td colspan="9" class="text-end text-h6">
              {{ main.date_of_create || "" }} г.
            </td>
          </tr>
          <tr>
            <td style="border-left: 1px solid black !important;">Ш.изм.</td>
            <td colspan="2" class="text-center text-uppercase text-h6"></td>
            <td colspan="2" style="border-left: 1px solid black !important;">
              Технол. извещение
            </td>
            <td
              colspan="2"
              class="text-center text-uppercase text-h6"
              style="border-right: 1px solid black !important;"
            >
              {{ main.number_technological_notification || "" }}
            </td>
            <td>Прим.</td>
            <td colspan="8" class="text-center text-h6">
              {{ main.note || "" }}
            </td>
          </tr>
          <tr>
            <td style="border-left: 1px solid black !important;">Партия</td>
            <td colspan="2" class="text-center text-uppercase text-h6">
              {{ main.party || "" }}
            </td>
            <td
              colspan="2"
              rowspan="2"
              style="border-left: 1px solid black !important; border-bottom: 1px solid black !important;"
            >
              № тех.док./ВТД
            </td>
            <td
              colspan="2"
              class="text-center text-uppercase text-h6"
              rowspan="2"
              style="border-right: 1px solid black !important; border-bottom: 1px solid black !important;"
            >
              {{ main.cipher_main_TD || "" }}
            </td>
            <td>Индекс</td>
            <td colspan="6" class="text-center text-h6">
              {{ main.designation || "" }}
            </td>
            <td>Лист</td>
            <td>{{ sheet }}</td>
          </tr>
          <tr>
            <td
              style="border-left: 1px solid black !important; border-bottom: 1px solid black !important;"
            >
              Таб. №
            </td>
            <td
              colspan="2"
              class="text-center text-uppercase text-subtitle-1 font-weight-bold"
              style="border-bottom: 1px solid black !important;"
            >
              {{ main.service_number || "" }}
            </td>
            <td>Код</td>
            <td colspan="6" class="text-center text-h6">
              {{ main.code_detail || "" }}
            </td>
            <td>Листов</td>
            <td>{{ sheets }}</td>
          </tr>
          <tr>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Доп. данные
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              № скв. опер.
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              № опер.
            </td>
            <td
              class="text-center text-caption"
              colspan="3"
              style="border-bottom: 1px solid black !important;"
            >
              Шифр оп. и вида работ, наимен. операции
            </td>
            <td
              class="text-center text-caption"
              style="border-bottom: 1px solid black !important;"
            >
              Код и наимен. оборудования
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Шифр проф.
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Р-д
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Шифр т.с.
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Вид норм.
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Ед. норм
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              Время
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              ТЦ
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 100px; width: 100px;border-bottom: 1px solid black !important;"
            >
              К-т зап.
            </td>
            <td
              class="text-center text-caption px-0"
              style="max-width: 50px; width: 50px;border-bottom: 1px solid black !important;"
            >
              № извещ.
            </td>
          </tr>
          <template v-for="(item, index) of operations" :data-id="index">
            <tr :key="`${index}-1`">
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-left: 1px solid black !important;"
              >
                {{ item.MoreDataShortString }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              >
                {{
                  (item.cipher_of_the_operation
                    ? item.cipher_of_the_operation.p018
                    : "") || ""
                }}
              </td>
              <td class="text-center text-subtitle-1"></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 30px; width: 30px;"
              ></td>
              <td class="text-center text-subtitle-1">
                {{
                  (item.hardware_cipher ? item.hardware_cipher.p501 : "") || ""
                }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 100px; width: 100px;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-right: 1px solid black !important;"
              ></td>
            </tr>
            <tr :key="`${index}-2`">
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important; border-left: 1px solid black !important;"
              ></td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.end_to_end_operation_number || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.operation_number || "" }}
              </td>
              <td
                class="text-center text-subtitle-1"
                colspan="3"
                style="border-bottom: 1px solid black !important;"
              >
                {{
                  item.cipher_of_the_operation
                    ? item.cipher_of_the_operation.p014
                    : ""
                }}
              </td>
              <td
                class="text-center text-subtitle-1"
                style="border-bottom: 1px solid black !important;"
              >
                {{
                  (item.hardware_cipher ? item.hardware_cipher.p451 : "") || ""
                }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{
                  (item.cipher_of_the_profession
                    ? item.cipher_of_the_profession.profcode70
                    : "") || ""
                }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.category_of_work || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.code_of_the_tariff_grid || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.type_of_norms || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.unit_of_the_rationong || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.time_rate_is_paid || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important;"
              >
                {{ item.norm_of_cycle_time || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 100px; width: 100px; border-bottom: 1px solid black !important;"
              >
                {{ item.launch_ratio || "" }}
              </td>
              <td
                class="text-center text-subtitle-1 px-0"
                style="max-width: 50px; width: 50px; border-bottom: 1px solid black !important; border-right: 1px solid black !important;"
              >
                {{ item.number_notification_sgt || "" }}
              </td>
            </tr>
            <tr
              v-if="item.countMoreDataFields() > 1"
              :key="`${index}-3`"
              style="height: 60px;"
            >
              <td
                colspan="16"
                class="text-center text-h6 font-weight-bold"
                style="border-left: 1px solid black !important; border-right: 1px solid black !important;"
              >
                Доп. данные к оп. {{ item.operation_number }}:
                {{ item.MoreDataLongString }}
              </td>
            </tr>
            <tr
              v-if="
                index + 1 !== operations.length &&
                  item.cipher_of_the_reference_tp !==
                    operations[index + 1].cipher_of_the_reference_tp
              "
              :key="`${index}-4`"
              style="height: 60px;"
            >
              <td
                colspan="16"
                class="text-center text-h6 font-weight-bold"
                style="border-left: 1px solid black !important; border-right: 1px solid black !important;"
              >
                Далее по т/п
                {{
                  operations[index + 1].cipher_of_the_reference_tp ||
                    main.cipher_main_TD.replace(/\s\/ (.*)/, "")
                }}
              </td>
            </tr>
          </template>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { saveDownloadedFile } from "@/helpers/browser";
import { countCardPages } from "@/helpers/operation";
import PageLoadingDialog from "@/components/PageLoadingDialog";
import CardActionBar from "@/components/CardActionBar";

export default {
  name: "ShowCardPage",
  components: {
    PageLoadingDialog,
    CardActionBar
  },
  computed: {
    ...mapState(["user"]),
    ...mapState("card", {
      main: state => state.card,
      operations: state => state.operations,
      pageLoading: state => state.isLoading,
      getExcelLoading: state => state.status.excel.loading
      // error: state => state.error,
    }),
    sheet() {
      return 1;
    },
    sheets() {
      return countCardPages(this.operations);
    }
  },
  data: () => ({
    cardID: null
  }),
  async mounted() {
    await this.fetchCardById(this.cardID);
  },
  created() {
    this.cardID = this.$route.params.id;
  },
  methods: {
    ...mapActions("card", ["fetchCardById", "getExcelFromCard"]),
    goEditMode() {
      this.$router.replace({ path: "/cards/edit/" + this.cardID });
    },
    async onGetExcel() {
      const data = await this.getExcelFromCard(this.cardID);
      saveDownloadedFile(data, `Карта норм времени.xlsx`);
    }
  }
};
</script>

<style lang="scss" src="./ShowCardPage.scss" />
