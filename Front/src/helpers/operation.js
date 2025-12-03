// import { Operation } from "@/models";

/**
 * Функция для расчёта количества листов в документе КНВ
 * @param { Operation[] } operations - Список операций
 * @returns { Number }
 */
export function countCardPages(operations) {
  // Каждая операция занимает как минимум одну строку
  let strings = operations.length;
  // Всего 2 стороны листа, на одной стороне карты 9 строк, каждая занимает 2 строки в Excel документе
  const stringsPerPage = 18;

  operations.forEach((operation, index) => {
    // Учитываем возможную строку для дополнительных данных операции
    if (operation.countMoreDataFields() > 1) {
      strings += 1;
    }
    // Учитываем возможную строку "Далее по т/п"
    if (
      index + 1 !== operations.length &&
      operation.cipher_of_the_reference_tp !==
        operations[index + 1].cipher_of_the_reference_tp
    ) {
      strings += 1;
    }
  });

  return Math.ceil(strings / stringsPerPage);
}
