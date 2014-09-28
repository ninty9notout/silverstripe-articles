<% cached 'relatedarticles', List(RelatedArticle).max(LastEdited), List(RelatedArticle).count() %>
	<section id="related-articles" class="article-listing" itemscope itemtype="http://schema.org/ItemList">
		<h2 class="heading"><span itemprop="name">Related Articles</span></h2>

		<div class="columns horizontal-gutters">
		<% loop RelatedArticles %>
			<article class="all-100 tablet-50" itemprop="itemListElement" itemscope itemtype="$Schema">
				<div class="columns half-horizontal-gutters">
					<a href="$Link" class="all-33 desktop-40" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
					<% if MainImage %>
						$MainImage.CroppedImage(229, 129)
						<% if MainImage.Content %><meta itemprop="caption" content="$MainImage.Content.XML"><% end_if %>
					<% else_if PlaceholderImage %>
						$PlaceholderImage.CroppedImage(229, 129)
					<% end_if %>
					</a>

					<div class="all-66 desktop-60">
						<h3><a href="$Link" itemprop="headline">$Title.XML</a></h3>
						<meta itemprop="url" content="$AbsoluteLink">
						<meta itemprop="description" content="<% if MetaDescription %>$MetaDescription.XML<% else %>$Content.Summary.XML<% end_if %>">
						<p class="meta">
							<a href="#">$Type</a> /
						<% if Category %>
							<a href="$Category.Link">$Category.Title.XML</a> /
						<% end_if %>
							<span itemscope itemprop="author" itemtype="http://schema.org/Person">
								<a href="#" itemprop="name">$Owner.Title.XML</a>
								<meta itemprop="url" content="#">
							</span> / 
							<time datetime="$Published.Rfc2822" itemprop="datePublished">$Published.Full</time>
						</p>
					</div>
				</div>
			</article>
		<% end_loop %>
		</div>
	</section><!-- #related-articles -->
<% end_cached %>