# Web UI Testing with CSS

Backend and API testing nowadays is very simple to set up with all the various solutions available for the different programming languages. When it comes to frontend testing there are also some decent solutions out there testing the correctness of the UI is still very often done manually. In this article we will discuss a simple way in order to support manual testing of your web UI.

For more automated tests please check the article `GIT Pre-Commit Hooks` or solutions such as `selenium`. However if you want to get immediate visual feedback of invalid HTML tags (e.g. missing attributes), inline styles etc. the following solution can be a great addition to your existing UI tests.

## CSS

Since developing a web application still involves a lot of looking at the actual application, CSS selectors can be used to create visual highlights in your web application if something isn't the way it's supposed to be. The idea is to create CSS selectors for invalid HTML logic which would cause warnings or errors during HTML validation, go against common standards or your internal guidelines by highlighting these structures in a very obvious way.

One way to do this is create a red border for the HTML element or make it blink etc. The more annoying it is the more likely you will fix it.

Since there are situation where I don't want to include these debugging styles I usually check for a URL parameter like `?debug` and if this is set include the debugging styles.

### Debugging Styles

In the following sections you can see some of my debugging selectors, feel free to add your own or search for already existing CSS debugging styles which suit your purpose. Some of these selectors are project specific or depend on your internal guidelines.

### General Highlighting Rule

The following rule defines how critical HTML should be highlighted.

```css
@keyframes blink { 
   0% { 
       border: 3px solid red;
   } 
}
```

### General Document Rules

The document language has to be set:

```css
html:not([lang]),
html[lang=" "],
html[lang=""] { 
    animation: blink .5s step-end infinite alternate;
 }
```

The document charset has to be defined and in my case should be `utf-8`:

```css
meta[charset]:not([charset="UTF-8"]),
meta[charset="UTF-8"]:not(:first-child) {
   animation: blink .5s step-end infinite alternate;
}
```

Highlight invalid viewport definitions:

```css
meta[name="viewport"][content*="user-scalable=no"],  
meta[name="viewport"][content*="maximum-scale"],  
meta[name="viewport"][content*="minimum-scale"] {
    animation: blink .5s step-end infinite alternate;
} 
```

Don't allow inline styles:

```css
*[style] {
    animation: blink .5s step-end infinite alternate;
}
```

### Forms

Forms must have a id, name and action:

```css
form:not([id]), form:not([id=""]),
form:not([name]):not([id]),
form:not([action]),
form[action=" "],
form[action=""] {
    animation: blink .5s step-end infinite alternate;
}
```

### Input Elements

Input, select and textarea elements as labels need to fulfill certain criteria such as having a type, id, and name:

```css
input:not([id]),  
input[type=""], 
select:not([id]),  
textarea:not([id]) {
    animation: blink .5s step-end infinite alternate;
}

label:not([for]),
label[for=""],
label[for=" "] {
    animation: blink .5s step-end infinite alternate;
}

input:not([name]),  
select:not([name]),  
textarea:not([name]) {
    animation: blink .5s step-end infinite alternate;
}
```

### Images

Images should always have a valid `alt` attribute and elements with the role `img` also should have valid aria-labels:

```css
img:not([alt]), 
img[alt=""],
img[alt=" "],
[role="img"]:not([aria-label]):not([aria-labelledby]),
svg[role="img"]:not([aria-label]):not([aria-labelledby]),
img[src=""],
img[src=" "],
img[src="#"],
img[src="/"], {
    animation: blink .5s step-end infinite alternate;
} 
```

### Links

In this section all empty links, links with invalid aria tags and invalid references will be highlighted:

```css
a:not([href]),
a:[href="#"],  
a:[href=""],  
a[href*="javascript:void(0)"],
a:empty
a:empty[title=""],
a:empty[aria-label=""],
a:empty[aria-labelledby=""],
a:empty:not([title]):not([aria-label]):not([aria-labelledby]),
a:blank[title=""],
a:blank[aria-label=""],
a:blank[aria-labelledby=""],
a:blank:not([title]):not([aria-label]):not([aria-labelledby]) {
    animation: blink .5s step-end infinite alternate;
}
```

### JavaScript on* Actions

Highlight `on*` actions which are deprecated in my framework:

```css
[onafterprint], [onbeforeprint], [onbeforeunload],
[onerror], [onhaschange], [onload], [onmessage],
[onoffline], [ononline], [onpagehide], [onpageshow],
[onpopstate], [onredo], [onresize], [onstorage],
[onundo], [onunload],
[onblur], [onchage], [oncontextmenu], [onfocus],
[onformchange], [onforminput], [oninput], [oninvalid],
[onreset], [onselect], [onsubmit],
[onkeydown], [onkeypress], [onkeyup],
[onclick], [ondblclick], [ondrag], [ondragend],
[ondragenter], [ondragleave], [ondragover],
[ondragstart], [ondrop], [onmousedown], [onmousemove],
[onmouseout], [onmouseover], [onmouseup], [onmousewheel],
[onscroll],
[onabort], [oncanplay], [oncanplaythrough],
[ondurationchange], [onemptied], [onended], [onerror],
[onloadeddata], [onloadedmetadata], [onloadstart],
[onpause], [onplay], [onplaying], [onprogress],
[onratechange], [onreadystatechange], [onseeked],
[onseeking], [onstalled], [onsuspend], [ontimeupdate],
[onvolumechange], [onwaiting] {
    animation: blink .5s step-end infinite alternate;
}
```

### Structure

It's also possible to highlight invalid or unwanted HTML structures such as missing `<td>` elements after a `<tr>`, `option` tags without a `select` etc:

```css
ul > :not(li),
ol > :not(li),
:not(ul):not(ol) > li {
    animation: blink .5s step-end infinite alternate;
}

:not(figure) > figcaption {
    animation: blink .5s step-end infinite alternate;
}

:not(tr) > td,
:not(tr) > th,
colgroup *:not(col),
:not(colgroup) > col,
tr > :not(td):not(th),
optgroup > :not(option),
:not(select) > optgroup,
:not(fieldset) > legend,
select > :not(option):not(optgroup),
:not(select):not(optgroup) > option,
table > *:not(thead):not(tfoot):not(tbody):not(tr):not(colgroup):not(caption) {
    animation: blink .5s step-end infinite alternate;
}
```

### User Specific Attributes

In my framework I make use of the `data-action` attributes. In my framework every tag that has this data binding must have a id.

```css
[data-action]:not([id]), [data-action]:not([id=""]) {
    animation: blink .5s step-end infinite alternate;
}

```

## Conclusion

CSS debugging/highlighting is a nice and easy to implement solution for a very fast visual feedback. On the other hand it is a very general solution which only helps to detect a small amount of potential problems which can be analyzed by specific tools more comprehensively.
