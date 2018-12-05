# Web UI Testing with CSS

Backend and API testing nowadays is very simple to set up with all the various solutions available for the different programming languages. When it comes to frontend testing there are also some decent solutions out there testing the correctness of the UI is still very often done manually. In this article we will discuss a simple way in order to support manual testing of your web UI.

For more automated tests please check the article `GIT Pre-Commit Hooks` or solutions such as `selenium`. However if you want to get immediate visual feedback of invalid HTML tags (e.g. missing attributes), inline styles etc. the following solution can be a great addition to your existing UI tests.

## CSS

Since developing a web application still involves a lot of looking at the actual application, CSS selectors can be used to create visual highlights in your web application if something isn't the way it's supposed to be. The idea is to create CSS selectors for invalid HTML logic which would cause warnings or errors during HTML validation, go against common standards or your internal guidelines by highlighting these structures in a very obvious way.

One way to do this is create a red border for the HTML element or make it blink etc. The more annoying it is the more likely you will fix it.

Since there are situation where I don't want to include these debugging styles I usually check for a URL parameter like `?debug` and if this is set include the debugging styles.

### Debugging Styles

In the following sections you can see some of my debugging selectors, feel free to add your own or search for already existing CSS debuging styles which suit your purpose.

### Forms

### Input Elements

### Images

### Links

### Empty/Invalid Attributes

### Accessibility

### Inline Styles

### 

## Conclusion

CSS debugging/highlighting is a nice and easy to implement solution for a very fast visual feedback. On the other hand it is a very general solution which only helps to detect a small amount of potential problems which can be analyzed by specific tools more comprehensively.
