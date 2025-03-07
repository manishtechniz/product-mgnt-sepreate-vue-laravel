# Product Management (V2)
----------------------------------

There are some new functionality.

## 1. Add confirm modal component

If you want use `confirm modal` then have to use global event `emitter.emit('open-confirm-modal', data);`

By default, has data `emitter.on('open-confirm-modal', open);`:
```js
const open = ({
    title: newTitle = "Are you sure",
    message: newMessage = "Are you sure you want to perform this action?",
    options: newOptions = {
        btnDisagree: "Disagree",
        btnAgree: "Agree",
    },
    agree = () => {},
    disagree = () => {},
}) => {
```