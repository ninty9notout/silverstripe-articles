<% cached 'articlelistingpartial', ID, ListingSize, LastEdited %>
	<article class="all-100 tablet-50 $FirstLast $EvenOdd" itemprop="itemListElement" itemscope itemtype="$Schema">
		<a href="$Link" class="media" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
		<% if MainImage %>
			$MainImage.CroppedImage(768, 432)
			<% if MainImage.Content %><meta itemprop="caption" content="$MainImage.Content.XML"><% end_if %>
		<% else_if PlaceholderImage %>
			$PlaceholderImage.CroppedImage(768, 432)
		<% end_if %>
		</a>

		<h3><a href="$Link" itemprop="headline">$Title.XML</a></h3>
		<meta itemprop="url" content="$AbsoluteLink">
		<p class="meta">
			<a href="$TypeLink">$Type</a> /
		<% if Category %>
			<a href="$Category.Link">$Category.Title.XML</a> /
		<% end_if %>
			<span itemscope itemprop="author" itemtype="http://schema.org/Person">
				<a href="#" itemprop="name">$Owner.Title.XML</a>
				<meta itemprop="url" content="#">
			</span> / 
			<time datetime="$Published.Rfc2822" itemprop="datePublished">$Published.Full</time>
		</p>
		<p class="description" itemprop="description">$Content.Summary</p>
	</article>
<% end_cached %>