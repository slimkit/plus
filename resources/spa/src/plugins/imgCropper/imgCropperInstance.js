import Vue from "vue";
import ImgCropper from "./imgCropper";

const prefixCls = "v-img-cropper";

/**
 * 圆形裁剪
 *     @author jsonleex <jsonlseex@163.com>
 */
function getRoundedCanvas(sourceCanvas) {
  var canvas = document.createElement("canvas");
  var context = canvas.getContext("2d");
  var width = sourceCanvas.width;
  var height = sourceCanvas.height;

  canvas.width = width;
  canvas.height = height;

  context.imageSmoothingEnabled = true;
  context.drawImage(sourceCanvas, 0, 0, width, height);
  context.globalCompositeOperation = "destination-in";
  context.beginPath();
  context.arc(
    width / 2,
    height / 2,
    Math.min(width, height) / 2,
    0,
    2 * Math.PI,
    true
  );
  context.fill();

  return canvas;
}

ImgCropper.newInstance = properties => {
  const _props = properties || {};

  const Instance = new Vue({
    data: Object.assign({}, _props, {
      url: "",
      round: true,
      visible: false
    }),
    computed: {
      disabled() {
        return true;
      }
    },
    created() {},
    methods: {
      close() {
        this.url = "";
        this.visible = false;
        this.onCancel();
        this.remove();
      },
      ok() {
        let data = this.$children[0].cropper.getCroppedCanvas({
          width: 500,
          height: 500
        });

        if (this.round) {
          data = getRoundedCanvas(data);
        }
        this.onOk(data);
        this.remove();
      },
      remove() {
        setTimeout(() => {
          this.destroy();
        }, 300);
      },
      destroy() {
        document.body.removeChild(this.$el);
        this.onRemove();
        this.$destroy();
      },
      onOk() {},
      onCancel() {},
      onRemove() {}
    },
    render(h) {
      let headerVNodes = [];

      headerVNodes.push(
        h(
          "div",
          {
            attrs: {
              class: `${prefixCls}-header`
            }
          },
          [
            h(
              "button",
              {
                attrs: {
                  class: `${prefixCls}-header-prepend`
                },
                on: {
                  click: this.close
                }
              },
              "取消"
            ),
            h(
              "div",
              {
                attrs: {
                  class: `${prefixCls}-header-title`
                },
                directives: [
                  {
                    name: "show",
                    value: this.title
                  }
                ]
              },
              this.title
            ),
            h(
              "button",
              {
                on: {
                  click: this.ok
                }
              },
              "完成"
            )
          ]
        )
      );

      let bodyVNodes = [];

      bodyVNodes.push(
        h(
          "div",
          {
            attrs: {
              class: `${prefixCls}-body`
            }
          },
          [
            h(ImgCropper, {
              props: {
                url: this.url,
                round: this.round
              }
            })
          ]
        )
      );

      return h(
        "div",
        {
          directives: [
            {
              name: "show",
              value: this.visible
            }
          ],
          attrs: {
            class: `${prefixCls}-wrap`
          }
        },
        [headerVNodes, bodyVNodes]
      );
    }
  });

  const component = Instance.$mount();
  document.body.appendChild(component.$el);
  const cropper = Instance.$children[0];
  return {
    show(option) {
      if ("url" in option) {
        cropper.$parent.url = option.url;
      }

      if ("round" in option) {
        cropper.$parent.round = option.round;
      }

      if ("onOk" in option) {
        cropper.$parent.onOk = option.onOk;
      }

      if ("onCancel" in option) {
        cropper.$parent.onCancel = option.onCancel;
      }

      if ("onRemove" in option) {
        cropper.$parent.onRemove = option.onRemove;
      }
      cropper.$parent.visible = true;
    },
    remove() {
      cropper.$parent.visible = false;
      cropper.$parent.remove();
    },
    component: cropper
  };
};

export default ImgCropper;
