function showAmount (amount) {
  const { walletRatio = 100 } = window.FEED;

  return amount / walletRatio * 100 + '(元)';
}

export {
  showAmount
};