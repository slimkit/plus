const URL = window.URL || window.webkitURL;
export default file => {
  const image = new Image();
  image.src = URL.createObjectURL(file);

  return new Promise(resolve => {
    image.onload = () => {
      const width = image.width;
      const height = image.height;

      const canvas = document.createElement("canvas");

      canvas.width = width;
      canvas.height = height;
      // 绘制图片帧（第一帧）
      canvas.getContext("2d").drawImage(image, 0, 0, width, height);
      resolve(canvas.toDataURL("image/png"));
    };
  });
};
