# Html - Hide & Show With Css Only (No Js)

Very often you want to show or hide UI elements with a simple click of a button. While this is trivial with JS it is often a little bit overkill and can be achieved with pure Html and CSS just as easily. 

## The Basic Idea

Create a checkbox that can be checked or unchecked and use this information to hide or show the following element.

## Implementation

First of all we need to create a checkbox input element before the element we want to hide or show.

```html
<input type="checkbox" id="iToggle">
<div id="elementToShowHide">
    Content can become visible
</div>
```

Of course we don't want to show a checkbox on our page so we have to hide it. And in this test case we also want to start out with a hidden div element which should become visible after clicking a button.

```css
#iToggle, #elementToShowHide {
    display: none;
}
```

This however means we cannot toggle the checkbox. Luckily we can use the label tag which can be clicked in order to check or un-check the checkbox. Two more advantages of using the label tag are that we can put it anywhere on the page (even after the element we want to show or hide). Additionally the label can contain content which means we can design a nice looking icon/button for our toggler. 

```html
<label for="iToggle">Click me <i class="fa fa-bars"></i></label>
```

So far this doesn't do anything apart from checking and un-checking the checkbox. Now we have to implement the magic which actually shows or hides the above defined element. This can be achieved with the `:checked` pseudo-class.

```css
#iToggle:checked + #elementToShowHide {
    display: block;
}
```

## Full Sample

```html
<input type="checkbox" id="iToggle">
<div id="elementToShowHide">
    Content can become visible
</div>

<label for="iToggle">Click me <i class="fa fa-bars"></i></label>
```

```css
#iToggle, #elementToShowHide {
    display: none;
}

#iToggle:checked + #elementToShowHide {
    display: block;
}
```

A working code example can be found here https://codepen.io/Orange-Management/pen/jvMLLM

## Next Steps

Of course this is only a very minimalist example showing the concept but it allows for a lot of great solutions. For example it's possible to add transition effects in order to fade in or fade out content. This concept can also be used with other pseudo-classes such as `:hover`, `:active`, etc.

## Usage

This technique can be used for many scenarios such as:

* Hamburger menu on mobile pages
* Popups based on user input
* Tooltips on `:hover`