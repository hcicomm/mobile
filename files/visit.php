var uid = '154194850216819366';var uuid = 'a9befd2b-8ab1-4214-a7c6-4107c5aa22f8';var sid = '154296068919166747';function ajaxActionPost(url, params, callback) {
var xhttp = new XMLHttpRequest();
xhttp.open('POST', url, true);
xhttp.onload = function (data) {
this.callback.call(this, data.srcElement.response);
};
xhttp.onerror = function () {
console.error(this.statusText);
};
xhttp.callback = callback;
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send(paramsStringify(params));
}

function paramsStringify(params) {
return Object.keys(params).reduce(function (p, c) {
var v = params[c];
if(v && v.indexOf('http') > -1) v = encodeURIComponent(params[c]);
p += c + '=' + v + '&'
return p;
}, '').slice(0, -1);
}

function getMeta() {
var metas = document.getElementsByTagName('meta');
var result = {};

for (var i = 0; i < metas.length; i++) {
var property = metas[i].getAttribute('property') || metas[i].getAttribute('name');
var content = metas[i].getAttribute('content');
if (property === null) continue;

if (property.indexOf(':') > -1) {
var keys = property.split(':');
var key1st = keys[0].trim();
var key2nd = keys[1].trim();

if (result[key1st] === undefined) result[key1st] = {};
if (key1st === 'article' && key2nd.indexOf('section') > -1) {
if (result[key1st]['section'] === undefined) result[key1st]['section'] = [];

var index = +(key2nd.replace('section', ''));
if (isNaN(index)) continue;
if (index > 0) --index;

result[key1st]['section'][index] = content;
}
else if (key1st === 'og' && key2nd === 'image') {
if (result[key1st][key2nd] === undefined) result[key1st][key2nd] = {};
if (keys.length === 2) result[key1st][key2nd]['link'] = content;
else result[key1st][key2nd][keys[2].trim()] = content;
} else {
result[key1st][key2nd] = content;
}
} else {
result[property] = content;
}
}

return result;
}

function getHost() {
return document.location.host;
}

function getTitle(title) {
title = title.replace(/&quot;/gi, '\"');
title = title.replace(/&lt;/gi, '<');
title = title.replace(/&gt;/gi, '>');
return title.trim();
}

(function () {
var url = 'http://discovery.newspic.kr';
var params = {
cpDomain: getHost()
};
var meta = getMeta();

if (meta.discovery !== undefined) {
params.articleId = meta.discovery.articleId;
if (meta.discovery.thumbnail !== undefined) params.articleThumbnailImgUrl = meta.discovery.thumbnail;
}

if (meta.og !== undefined) {
var og = meta.og;

var removePrefix = '기자 - ';
var titleTemp = getTitle(og.title);
var removeIdx = titleTemp.indexOf(removePrefix);
if(removeIdx > 0){
titleTemp = titleTemp.substring(removeIdx + removePrefix.length);
}

params.articleTitle = encodeURIComponent(titleTemp);
params.articleDescription = encodeURIComponent(og.description.trim());
params.articleLink = og.url;
if (og.imgae === undefined) params.articleImgUrl = og.image.link;
}

if(params.cpDomain == 'yachuk.com'){
params.articleId = meta.dable.item_id;
}

if (meta.article !== undefined) {
var article = meta.article;
params.articleCategory = article.section;
params.pubDate = article.published_time;
if (article.modified_time !== undefined) params.pubDate = article.modified_time;
}

if (params.articleImgUrl) {
ajaxActionPost(url + '/v1/log/visit', {
userId: uid,
uuid: uuid,
sid: sid,
cpDomain: params.cpDomain,
articleId: params.articleId,
referrer: document.referrer,
pubDate: params.pubDate
}, function (msg) {
var obj = JSON.parse(msg);
var isContent = obj.isContent;
if (isContent === 'CREATED' || isContent === 'MODIFIED') {
params.isContent = obj.isContent;
ajaxActionPost(url + '/v1/content/' + params.articleId, params, function (msg) {
});
}
});
}
})();