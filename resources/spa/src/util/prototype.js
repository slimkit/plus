// 补充 ES6 新提案 Promise.prototype.finally 在 iOS 下不生效的问题
if (typeof Promise.prototype.finally !== "function") {
  Promise.prototype.finally = function(callback) {
    let P = this.constructor;
    return this.then(
      value => P.resolve(callback()).then(() => value),
      reason =>
        P.resolve(callback()).then(() => {
          throw reason;
        })
    );
  };
}
