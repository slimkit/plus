import { shallowMount, mount } from '@vue/test-utils'
import Message from '@/components/common/message.vue'

describe('@/components/common/message.vue', () => {
  it('Check props type when passed', () => {
    const types = [
      { type: 'success', className: 'Alert--Success' },
      { type: 'warning', className: 'Alert--Warning' },
      { type: 'error', className: 'Alert--Error' },
    ]

    types.map(({ type, className }) => {
      let wrapper = shallowMount(Message, {
        propsData: { type },
      })

      expect(wrapper.classes()).toContain(className)
    })
  })

  it('Renders alert close DOM', () => {
    const wrapper = mount(Message)

    expect(wrapper.find('.Alert__close').exists()).toBe(true)
  })

  it('Renders default slot when passed', () => {
    const message = 'Default! Hello World~'
    const wrapper = shallowMount(Message)

    expect(wrapper.text()).toMatch(message)
  })

  it('Renders default slot', () => {
    const message = "Hi, I'm is a message.🎉"
    const wrapper = shallowMount(Message, {
      slots: {
        default: message,
      },
    })

    expect(wrapper.text()).toMatch(message)
  })
})
