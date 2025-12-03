export function objectHasNonEmptyStrings(object) {
  return Object.values(object).some(value => {
    return value.trim().length >= 1;
  });
}
