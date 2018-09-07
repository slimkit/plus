/**
  * 本地时间.
  *
  * @param {String} value
  * @return {String}
  * @author Seven Du <shiweidu@outlook.com>
  */
function localDate(value){
  return (new Date(`${value}Z`)).toLocaleString();
}
/**
  * Local date to UTC.
  *
  * @param {String} value
  * @return {String}
  * @author Seven Du <shiweidu@outlook.com>
  */
function localDateToUTC(value) {
  const dateRepo = new Date(value);
  const fullYear = dateRepo.getUTCFullYear();
  const month = dateRepo.getUTCMonth() + 1;
  const date = dateRepo.getUTCDate();

  return `${fullYear}-${month}-${date}`;
}

export {
  localDateToUTC,
  localDate
};