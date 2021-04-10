export default function(svgelem) {
    var bbox = svgelem.getBBox();
    if (bbox && bbox.width && bbox.height) {
        svgelem.setAttribute(
            'viewBox',
            [bbox.x, bbox.y, Math.max(60, bbox.width), bbox.height].join(' ')
        );
        svgelem.removeAttribute('width');
        svgelem.removeAttribute('height');
    }
}
