# CSS Only Alternatives

We can create beautiful and complex UI solutions with JavaScript for a comfortable and seamless user experience. What we often forget is that there can be a more simple and more performant solution. After all JavaScript can add additional performance hits due to loading times, slow execution or previous errors. Furthermore, JavaScript adds complexity which might not be necessary.

So how does the alternative look like? Well, it is boring but HTML + CSS. Of course it’s not possible to completely replace JavaScript and neither should you force the usage of HTML + CSS if it doesn’t solve your problem or requires jumping through multiple hoops just to avoid JavaScript. However, it is definitely worth to stop for a second, lean back and think if a HTML + CSS solution could be possible.

A whole suite of UI functionality can be implemented with radio or checkbox input elements and CSS. Some cases are:

* Expanding/Minimizing side navigation
* Tabs
* Details/More/Advanced section which can be hidden
* Accordion
* Showing/Hiding links in a category
* Small image gallery
* UI Popups
* ...

## Concept

The general concept is to use a label which a user can click to check or uncheck a checkbox/radio element. The input element must be placed in front oft he UI component you want to manipulate.

```html
// This label can be anywhere on the page
<label for=“myCheckboxId“>The UI element you want to be clickable. E.g. button, link, image, ...<label>

// Checkbox directly in front of the element to show/hide
<input id=“ myCheckboxId“ type=“checkbox“ checked>
<div>Element to show/hide</div>
```

Now we need to define the CSS to show and hide our element based on the checked status:

```css
#myCheckboxId, #myCheckboxId+div {
	display: none;
}

#myCheckboxId:checked+div {
	display: block;
}
```

Now whenever a user clicks on the label the content in our div is shown or hidden. This simple concept can be applied to many different use cases as described above.

## Examples

### Gallery

<iframe src="/content/blog/dev/20221129/gallery.htm" height="650px"></iframe>

### Tabs

<iframe src="/content/blog/dev/20221129/tab.htm" height="500px"></iframe>

### Accordion

<iframe src="/content/blog/dev/20221129/accordion.htm" height="500px"></iframe>
