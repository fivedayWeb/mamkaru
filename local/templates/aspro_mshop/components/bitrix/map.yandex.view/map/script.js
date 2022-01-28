if (!window.BX_YMapAddPlacemark)
{
	window.BX_YMapAddPlacemark = function(map, arPlacemark, isClustered)
	{
		if (null == map)
			return false;

		if(!arPlacemark.LAT || !arPlacemark.LON)
			return false;

		var props = {};
		
		if (null != arPlacemark.TEXT && arPlacemark.TEXT.length > 0)
		{
			var value_view = '';
			var balloonContent = arPlacemark.HTML;

			if (arPlacemark.TEXT.length > 0)
			{
				var rnpos = arPlacemark.TEXT.indexOf("\n");
				value_view = rnpos <= 0 ? arPlacemark.TEXT : arPlacemark.TEXT.substring(0, rnpos);
			}

			if(arPlacemark.HTML === undefined || !arPlacemark.HTML.length){
				balloonContent = arPlacemark.TEXT.replace(/\n/g, '<br />');
			}

			props.balloonContent = balloonContent;			
			props.hintContent = value_view;
		}

		//var root = '/bitrix/templates/aspro_mshop/images/map_marker.png';

		var markerSVG = ymaps.templateLayoutFactory.createClass([
		'<svg class="yandex_map_placemark" xmlns="http://www.w3.org/2000/svg" width="45" height="57" viewBox="0 0 45 57" fill="none">',
		'<path fill-rule="evenodd" clip-rule="evenodd" d="M22.5 57C21.5646 57 20.6827 56.5637 20.1152 55.8201L5.89467 37.1874C2.11217 33.1253 0.0048883 27.7804 0 22.2257L7.77245e-05 22.2013C0.0430865 16.2706 2.43992 10.5998 6.66345 6.43636C10.8819 2.27797 16.5775 -0.0365067 22.5 0.000435535C28.4225 -0.0365067 34.1181 2.27797 38.3365 6.43636C42.5601 10.5998 44.9569 16.2706 44.9999 22.2013L45 22.2257C44.9951 27.7804 42.8878 33.1253 39.1053 37.1874L24.8848 55.8201C24.3173 56.5637 23.4354 57 22.5 57Z" fill="white" fill-opacity="0.5"/>',
		'<path class="colored_theme_svg" fill-rule="evenodd" clip-rule="evenodd" d="M36.8129 35.2463L22.5 54L8.18707 35.2463C4.85995 31.7261 3.00426 27.067 3 22.223C3.03724 17.0878 5.11259 12.1777 8.76952 8.57283C12.4264 4.96794 17.3654 2.96352 22.5 3.0005C27.6346 2.96352 32.5736 4.96794 36.2305 8.57283C39.8874 12.1777 41.9628 17.0878 42 22.223C41.9957 27.067 40.14 31.7261 36.8129 35.2463V35.2463Z"/>',
		'<path d="M22.5 33C28.299 33 33 28.299 33 22.5C33 16.701 28.299 12 22.5 12C16.701 12 12 16.701 12 22.5C12 28.299 16.701 33 22.5 33Z" fill="white"/>',
		'</svg>'
		].join(''));
		
		var obPlacemark = new ymaps.Placemark(
			[arPlacemark.LAT, arPlacemark.LON],
			props,
			{
				balloonCloseButton: true,
				iconLayout: markerSVG,
				//iconImageHref: root,
				//iconImageSize: [45, 57],
				//iconImageOffset: [-23, -57],
			}
		);

		//map.geoObjects.add(obPlacemark);
		if(!isClustered) { map.geoObjects.add(obPlacemark); }

		return obPlacemark;
	}
}

if (!window.BX_YMapAddPolyline)
{
	window.BX_YMapAddPolyline = function(map, arPolyline)
	{
		if (null == map)
			return false;

		if (null != arPolyline.POINTS && arPolyline.POINTS.length > 1)
		{
			var arPoints = [];
			for (var i = 0, len = arPolyline.POINTS.length; i < len; i++)
			{
				arPoints.push([arPolyline.POINTS[i].LAT, arPolyline.POINTS[i].LON]);
			}
		}
		else
		{
			return false;
		}

		var obParams = {clickable: true};
		if (null != arPolyline.STYLE)
		{
			obParams.strokeColor = arPolyline.STYLE.strokeColor;
			obParams.strokeWidth = arPolyline.STYLE.strokeWidth;
		}
		var obPolyline = new ymaps.Polyline(
			arPoints, {balloonContent: arPolyline.TITLE}, obParams
		);

		map.geoObjects.add(obPolyline);

		return obPolyline;
	}
}