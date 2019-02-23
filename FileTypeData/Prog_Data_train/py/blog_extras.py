from django import template

register = template.Library()


@register.filter
def social_share_image(article, request):
    image = article.preview_image['article'].url
    if 'HTTP_USER_AGENT' in request.META:
        ua = request.META['HTTP_USER_AGENT']
        if ua and 'vk.com' in ua:
            image = article.preview_image['small_article'].url
    return image