import markdownIt from 'markdown-it'
import plusImagePlugin from 'markdown-it-plus-image'
import highlight from 'highlight.js'
import container from 'markdown-it-container'
import { baseURL } from '@/api'

/**
 * Create a markdown it instance.
 *
 * @type {Object}
 */
export const markdown = markdownIt({
  breaks: true,
  html: true,
  highlight: function (code) {
    return highlight ? highlight.highlightAuto(code).value : code
  },
}).use(plusImagePlugin, `${baseURL}/files/`)
  .use(container, 'hljs-left') /* align left */
  .use(container, 'hljs-center')/* align center */
  .use(container, 'hljs-right')

/**
 * Markdown render.
 *
 * @param {string} markdownText
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function render (markdownText) {
  return markdown.render(String(markdownText))
}

/**
 * Synyax Text AND images.
 *
 * @param {string} markdownText
 * @return {Object: { text: String, images: Array }}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function syntaxTextAndImage (markdownText) {
  /**
   * Get markdown text rende to HTML string.
   *
   * @type {string}
   */
  const html = render(markdownText)

  /**
   * Match all images HTML code in `html`
   *
   * @type {Array}
   */
  const imageHtmlCodes = html.match(/<img.*?(?:>|\/>)/gi)

  /**
   * Images.
   *
   * @type {Array}
   */
  let images = []

  // For each all image.
  if (imageHtmlCodes instanceof Array) {
    imageHtmlCodes.forEach(function (imageHtmlCode) {
      /**
       * Match img HTML tag src attr.
       *
       * @type {Array}
       */
      let result = imageHtmlCode.match(/src=['"]?([^'"]*)['"]?/i)

      // If matched push to images array.
      if (result !== null && result[1]) {
        images.push(result[1])
      }
    })
  }

  /**
   * Replace all HTML tag to '', And replace img HTML tag to "[图片]"
   *
   * @type {string}
   */
  const text = html
    .replace(/<img.*?(?:>|\/>)/gi, '[图片]') // Replace img HTML tag to "[图片]"
    .replace(/<\/?.+?>/gi, '') // Removed all HTML tags.
    .replace(/ /g, '') // Removed all empty character.

  // Return all matched result.
  // {
  //    images: Array,
  //    text: string
  // }
  return { images, text }
}

/**
 * Export default, export render function.
 */
export default render
