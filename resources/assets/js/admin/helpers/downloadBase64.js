export default function(contents, filename) {
    var uri = 'data:application/octet-stream;base64,' + contents;
    var link = document.createElement('a');
    link.setAttribute('download', filename);
    link.setAttribute('href', uri);
    link.setAttribute('target', '_blank');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(body.lastChild);
}
